<?php
class Password
{
    private int $length;
    private bool $useUpper;
    private bool $useNumbers;
    private bool $useSymbols;

    public function __construct(int $length = 12, bool $useUpper = true, bool $useNumbers = true, bool $useSymbols = false)
    {
        if ($length < 6) {
            throw new InvalidArgumentException("La longitud mínima recomendada es 6 caracteres.");
        }
        $this->length = $length;
        $this->useUpper = $useUpper;
        $this->useNumbers = $useNumbers;
        $this->useSymbols = $useSymbols;
    }

    public function generate(): string
    {
        $lower = 'abcdefghijklmnopqrstuvwxyz';
        $upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $symbols = '!@#$%*-_+=?';

        $pool = $lower;
        if ($this->useUpper) $pool .= $upper;
        if ($this->useNumbers) $pool .= $numbers;
        if ($this->useSymbols) $pool .= $symbols;

        $passwordChars = [];
        $passwordChars[] = $lower[random_int(0, strlen($lower) - 1)];
        if ($this->useUpper) $passwordChars[] = $upper[random_int(0, strlen($upper) - 1)];
        if ($this->useNumbers) $passwordChars[] = $numbers[random_int(0, strlen($numbers) - 1)];
        if ($this->useSymbols) $passwordChars[] = $symbols[random_int(0, strlen($symbols) - 1)];

        $remaining = $this->length - count($passwordChars);
        for ($i = 0; $i < $remaining; $i++) {
            $passwordChars[] = $pool[random_int(0, strlen($pool) - 1)];
        }

        $this->aleatorio($passwordChars);

        return implode('', $passwordChars);
    }


    public function generateWithHash(int|string $algo = PASSWORD_DEFAULT): array
    {
        $pwd = $this->generate();
        $hash = password_hash($pwd, $algo);
        return ['password' => $pwd, 'hash' => $hash];
    }

    public function verifyPassword(string $plainPassword, string $hash): bool
    {
        return password_verify($plainPassword, $hash);
    }

    private function aleatorio(array &$array): void
    {
        $n = count($array);
        for ($i = $n - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            $tmp = $array[$i];
            $array[$i] = $array[$j];
            $array[$j] = $tmp;
        }
    }
}
//CONTRASENA EN TEXTO PLANO: oT6dtoKWR3vd