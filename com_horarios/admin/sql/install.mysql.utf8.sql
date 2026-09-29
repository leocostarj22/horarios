CREATE TABLE IF NOT EXISTS `#__horarios_municipios` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL DEFAULT '',
    `alias` VARCHAR(255) NOT NULL DEFAULT '',
    `image` VARCHAR(1024) NOT NULL DEFAULT '',
    `sidebar_image` VARCHAR(1024) NOT NULL DEFAULT '',
    `map_link` VARCHAR(1024) NOT NULL DEFAULT '',
    `mapa_file` VARCHAR(1024) NOT NULL DEFAULT '',
    `brochura_file` VARCHAR(1024) NOT NULL DEFAULT '',
    `reservas_url` VARCHAR(1024) NOT NULL DEFAULT '',
    `state` TINYINT NOT NULL DEFAULT 1,
    `ordering` INT NOT NULL DEFAULT 0,
    `params` MEDIUMTEXT,
    `created` DATETIME NULL,
    `created_by` INT UNSIGNED NOT NULL DEFAULT 0,
    `modified` DATETIME NULL,
    `modified_by` INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx_state` (`state`),
    KEY `idx_alias` (`alias`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `#__horarios_circuitos` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `municipio_id` INT UNSIGNED NOT NULL DEFAULT 0,
    `parent_id` INT UNSIGNED NOT NULL DEFAULT 0,
    `title` VARCHAR(255) NOT NULL DEFAULT '',
    `alias` VARCHAR(255) NOT NULL DEFAULT '',
    `folheto_file` VARCHAR(1024) NOT NULL DEFAULT '',
    `circuito_file` VARCHAR(1024) NOT NULL DEFAULT '',
    `horario_file` VARCHAR(1024) NOT NULL DEFAULT '',
    `tarifario_file` VARCHAR(1024) NOT NULL DEFAULT '',
    `state` TINYINT NOT NULL DEFAULT 1,
    `ordering` INT NOT NULL DEFAULT 0,
    `params` MEDIUMTEXT,
    `created` DATETIME NULL,
    `created_by` INT UNSIGNED NOT NULL DEFAULT 0,
    `modified` DATETIME NULL,
    `modified_by` INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx_municipio` (`municipio_id`),
    KEY `idx_parent` (`parent_id`),
    KEY `idx_state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `#__horarios_banners` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL DEFAULT '',
    `image` VARCHAR(1024) NOT NULL DEFAULT '',
    `link_url` VARCHAR(1024) NOT NULL DEFAULT '',
    `publish_up` DATETIME NULL,
    `publish_down` DATETIME NULL,
    `state` TINYINT NOT NULL DEFAULT 1,
    `ordering` INT NOT NULL DEFAULT 0,
    `created` DATETIME NULL,
    `created_by` INT UNSIGNED NOT NULL DEFAULT 0,
    `modified` DATETIME NULL,
    `modified_by` INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx_state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `#__horarios_reservas` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL DEFAULT '',
    `image` VARCHAR(1024) NOT NULL DEFAULT '',
    `link_url` VARCHAR(1024) NOT NULL DEFAULT '',
    `state` TINYINT NOT NULL DEFAULT 1,
    `ordering` INT NOT NULL DEFAULT 0,
    `created` DATETIME NULL,
    `created_by` INT UNSIGNED NOT NULL DEFAULT 0,
    `modified` DATETIME NULL,
    `modified_by` INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    KEY `idx_state` (`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
