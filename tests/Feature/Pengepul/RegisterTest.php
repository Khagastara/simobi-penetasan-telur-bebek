<?php

namespace Tests\Feature\Pengepul;

use App\Models\Akun;
use App\Models\Pengepul;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function pengepul_can_view_registration_form()
    {
        $response = $this->get(route('pengepul.register'));

        $response->assertSuccessful();
        $response->assertViewIs('homepage.pages.pengepul.register');
    }

    #[Test]
    public function pengepul_can_register_with_valid_data()
    {
        $response = $this->post(route('pengepul.register'), [
            'nama' => 'John Doe',
            'no_hp' => '081234567890',
            'email' => 'john@example.com',
            'username' => 'johndoe',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('pengepul.login'));
        $response->assertSessionHas('success', 'Akun berhasil dibuat. Silakan login.');

        $this->assertDatabaseHas('akuns', [
            'username' => 'johndoe',
            'email' => 'john@example.com',
        ]);

        $akun = Akun::first();
        $this->assertDatabaseHas('pengepuls', [
            'nama' => 'John Doe',
            'no_hp' => '081234567890',
            'id_akun' => $akun->id,
        ]);

        // Verify password hashing
        $this->assertTrue(Hash::check('password123', $akun->password));
    }

    #[Test]
    public function registration_fails_without_required_fields()
    {
        $response = $this->post(route('pengepul.register'), []);

        $response->assertSessionHasErrors([
            'nama', 'no_hp', 'email', 'username', 'password'
        ]);

        // Verify custom error messages
        $response->assertSessionHasErrors([
            'nama' => 'Data nama wajib diisi',
            'no_hp' => 'Data no hp wajib diisi',
            'email' => 'Data email wajib diisi',
            'username' => 'Data username wajib diisi',
            'password' => 'Data password wajib diisi',
        ]);
    }

    #[Test]
    public function registration_fails_with_duplicate_email()
    {
        Akun::factory()->create(['email' => 'existing@example.com']);

        $response = $this->post(route('pengepul.register'), [
            'nama' => 'John Doe',
            'no_hp' => '081234567890',
            'email' => 'existing@example.com',
            'username' => 'johndoe',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email' => 'Email telah terdaftar']);
    }

    #[Test]
    public function registration_fails_with_duplicate_username()
    {
        Akun::factory()->create(['username' => 'takenusername']);

        $response = $this->post(route('pengepul.register'), [
            'nama' => 'John Doe',
            'no_hp' => '081234567890',
            'email' => 'john@example.com',
            'username' => 'takenusername',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['username' => 'Username telah terdaftar']);
    }

    #[Test]
    public function registration_fails_with_duplicate_phone_number()
    {
        $akun = Akun::factory()->create();
        Pengepul::factory()->create([
            'no_hp' => '081234567890',
            'id_akun' => $akun->id
        ]);

        $response = $this->post(route('pengepul.register'), [
            'nama' => 'John Doe',
            'no_hp' => '081234567890',
            'email' => 'john@example.com',
            'username' => 'johndoe',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['no_hp' => 'Nomor HP telah terdaftar']);
    }

    #[Test]
    public function registration_fails_with_unmatched_password_confirmation()
    {
        $response = $this->post(route('pengepul.register'), [
            'nama' => 'John Doe',
            'no_hp' => '081234567890',
            'email' => 'john@example.com',
            'username' => 'johndoe',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertSessionHasErrors(['password' => 'Konfirmasi password tidak sesuai']);
    }

    #[Test]
    public function registration_fails_with_invalid_email_format()
    {
        $response = $this->post(route('pengepul.register'), [
            'nama' => 'John Doe',
            'no_hp' => '081234567890',
            'email' => 'invalid-email',
            'username' => 'johndoe',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email' => 'Format email tidak valid.',]);
    }

    #[Test]
    public function registration_fails_with_long_fields()
    {
        $longString = str_repeat('a', 256);

        $response = $this->post(route('pengepul.register'), [
            'nama' => $longString,
            'no_hp' => '081234567890',
            'email' => 'john@example.com',
            'username' => $longString,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'nama' => 'The nama may not be greater than 255 characters.',
            'username' => 'The username may not be greater than 255 characters.',
        ]);
    }

    #[Test]
    public function registration_does_not_store_timestamps()
    {
        $this->post(route('pengepul.register'), [
            'nama' => 'John Doe',
            'no_hp' => '081234567890',
            'email' => 'john@example.com',
            'username' => 'johndoe',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $akun = Akun::first();
        $pengepul = Pengepul::first();

        $this->assertNull($akun->created_at);
        $this->assertNull($akun->updated_at);
        $this->assertNull($pengepul->created_at);
        $this->assertNull($pengepul->updated_at);
    }
}
