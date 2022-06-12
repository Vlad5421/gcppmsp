$sql = "CREATE TABLE `zapis_card` (\n"

    . "  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,\n"

    . "  `id_parrent` VARCHAR(11) NOT NULL,\n"

    . "  `organnapr` VARCHAR(50) NOT NULL,\n"

    . "  `prich` VARCHAR(50) NOT NULL,\n"

    . "  `fioreb` VARCHAR(50) NOT NULL,\n"

    . "  `dateroj` DATE NOT NULL,\n"

    . "  `school` VARCHAR(50) NOT NULL,\n"

    . "  `class` VARCHAR(20) NOT NULL,\n"

    . "  `datapredpmpk` DATE NOT NULL,\n"

    . "  `namepredpmpk` VARCHAR(50) NOT NULL,\n"

    . "  `snopdrod` BOOLEAN NOT NULL DEFAULT FALSE,\n"

    . "  `snopdreb` BOOLEAN NOT NULL DEFAULT FALSE,\n"

    . "  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP\n"

    . ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
