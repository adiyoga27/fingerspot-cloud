<?php

namespace App;

enum ScanType
{
    const MASUK = 0;
    const PULANG = 1;
    const ISTIRAHAT = 2; //scan istrihat
    const KEMBALI = 3; //scan kembali dari istirahat
    const LEMBUR = 4; //scan lembur
    const PULANG_LEMBUR = 5; //scan pulang lembur
    const RAPAT = 6; //scan keluar lembur
    const PULANG_RAPAT = 7;

}
