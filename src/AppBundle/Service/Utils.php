<?php

namespace AppBundle\Service;

class Utils
{
    public function getNiveau(int $xp, string $courbe)
    {
        switch($courbe) {
            case 'R':
                return pow($xp / 0.8, 1 / 3);
            case 'M':
                return pow($xp, 1 / 3);
            case 'P':
                return pow($xp / 1.2, 1 / 3);
            case 'L':
                return pow($xp / 1.25, 1 / 3);
            default:
                return 5;
        }

    }
    
    public function getXp(int $n, string $courbe)
    {
        switch($courbe) {
            case 'R':
                return 0.8 * pow($n, 3);
            case 'M':
                return pow($n, 3);
                break;
            case 'P':
                return 1.2 * pow($n, 3) - 15 * pow($n, 2) + 100 * $n - 140;
                break;
            case 'L':
                return 1.25 * pow($n, 3);
                break;
            default:
                return 0;
        }

    }
}