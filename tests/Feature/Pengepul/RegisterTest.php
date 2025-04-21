    <?php

    namespace Tests\Feature\Pengepul;

    use App\Models\Akun;
    use App\Models\Pengepul;
    use Illuminate\Foundation\Testing\RefreshDatabase;
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
            $response->assertSessionHas('success');

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
        }

        #[Test]
        public function registration_fails_without_required_fields()
        {
            $response = $this->post(route('pengepul.register'), []);

            $response->assertSessionHasErrors([
                'nama', 'no_hp', 'email', 'username', 'password'
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

            $response->assertSessionHasErrors(['email']);
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

            $response->assertSessionHasErrors(['username']);
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

            $response->assertSessionHasErrors(['no_hp']);
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

            $response->assertSessionHasErrors(['password']);
        }
    }
