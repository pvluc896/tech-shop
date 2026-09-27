<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Email hoặc mật khẩu không chính xác.'])->onlyInput('email');
        }
        if ($request->user()->is_locked) {
            Auth::logout();

            return back()->withErrors(['email' => 'Tài khoản của bạn đang bị khóa. Vui lòng liên hệ quản trị viên.'])->onlyInput('email');
        }
        $request->session()->regenerate();

        $user = $request->user();
        $cart = $user->cart ?? [];
        foreach ($request->session()->get('cart', []) as $productId => $quantity) {
            $cart[$productId] = max((int) ($cart[$productId] ?? 0), (int) $quantity);
        }
        $user->update(['cart' => $cart]);
        $request->session()->put('cart', $cart);

        return redirect()->intended('/')->with('success', 'Đăng nhập thành công.');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        User::create($data);

        return redirect()->route('login')->with('success', 'Đăng ký tài khoản thành công. Vui lòng đăng nhập để tiếp tục.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Bạn đã đăng xuất.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetCode(Request $request)
    {
        $data = $request->validate(['email' => ['required', 'email', 'exists:users,email']], ['email.exists' => 'Email này chưa có tài khoản.']);
        $code = (string) random_int(100000, 999999);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $data['email']],
            ['token' => Hash::make($code), 'created_at' => now()]
        );
        Mail::raw("Mã đặt lại mật khẩu Luc Tech Shop của bạn là: {$code}. Mã có hiệu lực trong 10 phút.", function ($message) use ($data) {
            $message->to($data['email'])->subject('Mã đặt lại mật khẩu - Luc Tech Shop');
        });

        return redirect()->route('password.code.form', ['email' => $data['email']])->with('success', 'Mã đặt lại mật khẩu đã được gửi tới Gmail của bạn.');
    }

    public function showVerifyResetCode(Request $request)
    {
        return view('auth.verify-reset-code', ['email' => $request->query('email')]);
    }

    public function verifyResetCode(Request $request)
    {
        $data = $request->validate(['email' => ['required', 'email'], 'code' => ['required', 'digits:6']]);
        $reset = DB::table('password_reset_tokens')->where('email', $data['email'])->first();
        if (! $reset || now()->greaterThan(Carbon::parse($reset->created_at)->addMinutes(10)) || ! Hash::check($data['code'], $reset->token)) {
            return back()->withErrors(['code' => 'Mã xác thực không đúng hoặc đã hết hạn.'])->withInput(['email' => $data['email']]);
        }
        $request->session()->put([
            'password_reset_email' => $data['email'],
            'password_reset_expires_at' => now()->addMinutes(10)->timestamp,
        ]);

        return redirect()->route('password.reset');
    }

    public function showResetPassword(Request $request)
    {
        if (! $this->hasValidPasswordResetSession($request)) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        if (! $this->hasValidPasswordResetSession($request)) {
            return redirect()->route('password.request');
        }

        $email = $request->session()->get('password_reset_email');
        $data = $request->validate(['password' => ['required', 'confirmed', Password::defaults()]]);
        User::where('email', $email)->update(['password' => Hash::make($data['password'])]);
        DB::table('password_reset_tokens')->where('email', $email)->delete();
        $request->session()->forget(['password_reset_email', 'password_reset_expires_at']);

        return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập.');
    }

    private function hasValidPasswordResetSession(Request $request): bool
    {
        $expiresAt = $request->session()->get('password_reset_expires_at');

        if ($request->session()->has('password_reset_email') && $expiresAt && now()->timestamp <= $expiresAt) {
            return true;
        }

        $request->session()->forget(['password_reset_email', 'password_reset_expires_at']);

        return false;
    }

    public function profile()
    {
        return view('auth.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'phone' => ['nullable', 'regex:/^0\d{9}$/', 'unique:users,phone,'.$user->id],
            'birthday' => ['nullable', 'date', 'before_or_equal:today'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:1000'],
        ], [
            'phone.regex' => 'Số điện thoại phải có 10 chữ số và bắt đầu bằng số 0.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'birthday.before_or_equal' => 'Ngày sinh không được sau ngày hiện tại.',
        ]);
        if ($data['email'] !== $user->email) {
            $data['email_verified_at'] = null;
        }
        $user->update($data);

        return back()->with('success', 'Thông tin cá nhân đã được cập nhật.');
    }

    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);
        $request->user()->update(['password' => Hash::make($data['password'])]);

        return back()->with('success', 'Mật khẩu đã được thay đổi.');
    }

    public function showVerifyEmail()
    {
        return view('auth.verify-email');
    }

    public function sendVerificationCode(Request $request)
    {
        $user = $request->user();
        $code = (string) random_int(100000, 999999);
        $user->update([
            'email_verification_code' => Hash::make($code),
            'email_verification_code_expires_at' => now()->addMinutes(10),
        ]);
        Mail::raw("Mã xác thực Luc Tech Shop của bạn là: {$code}. Mã có hiệu lực trong 10 phút.", function ($message) use ($user) {
            $message->to($user->email)->subject('Mã xác thực email - Luc Tech Shop');
        });

        return back()->with('success', 'Mã xác thực đã được gửi tới email của bạn.');
    }

    public function verifyEmail(Request $request)
    {
        $request->validate(['code' => ['required', 'digits:6']]);
        $user = $request->user();
        if (! $user->email_verification_code || ! $user->email_verification_code_expires_at?->isFuture() || ! Hash::check($request->code, $user->email_verification_code)) {
            return back()->withErrors(['code' => 'Mã xác thực không đúng hoặc đã hết hạn.']);
        }
        $user->update(['email_verified_at' => now(), 'email_verification_code' => null, 'email_verification_code_expires_at' => null]);

        return redirect()->route('profile')->with('success', 'Email đã được xác thực thành công.');
    }
}
