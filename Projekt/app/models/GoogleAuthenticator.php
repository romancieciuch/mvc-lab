<?php

declare(strict_types=1);
namespace App\Models;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Common\EccLevel;
use OTPHP\TOTP;

require_once LIB_DIR . "vendor/autoload.php";

class GoogleAuthenticator {

    /**
     * 1. Generuje sekretny klucz dla użytkownika w formacie Base32.
     * Google Auth wymaga klucza składającego się WYŁĄCZNIE ze znaków A-Z oraz 2-7.
     */
    public function generate_secret (int $length = 16) : string {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $secret = '';

        for ($i = 0; $i < $length; $i++)
            $secret .= $alphabet[random_int(0, strlen($alphabet) - 1)];

        return $secret;
    }

    /**
     * 2. Buduje specjalny link (Provisioning URI), który Google Auth potrafi odczytać.
     */
    public function get_provisioning_uri (string $email, string $secret): string {
        $issuer = 'FLOW';
        $encodedIssuer = rawurlencode($issuer);
        $encodedAccount = rawurlencode($email);

        return "otpauth://totp/{$encodedIssuer}:{$encodedAccount}?secret={$secret}&issuer={$encodedIssuer}";
    }

    /**
     * 3. Zamienia wygenerowany link na gotowy kod SVG z użyciem chillerlan.
     */
    public function generate_qr_svg (string $uri): string {
		$options = new QROptions([
			'outputBase64' => false,
			'eccLevel'     => EccLevel::L,
			'addQuietzone' => false
		]);

        $qrcode = new QRCode($options);
        return $qrcode->render($uri);
    }

    /**
     * 4. Weryfikuje wpisany przez użytkownika kod z aplikacji.
     * @param string $secret Tajny klucz przypisany do użytkownika (z bazy danych)
     * @param string $userCode 6-cyfrowy kod wpisany w formularzu
     * @return bool Wynik weryfikacji
     */
    public function verify_code (string $secret, string $userCode): bool {
        try {
            $totp = TOTP::createFromSecret($secret);
            return $totp->verify($userCode);

        } catch (\Exception $e) {

            return false;
        }
    }
}