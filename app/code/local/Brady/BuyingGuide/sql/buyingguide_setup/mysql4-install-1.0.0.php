<?php

$this->startSetup();
$this->run("
		CREATE TABLE IF NOT EXISTS `buying_guide` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `sku` varchar(255) NOT NULL,
		  `buying_guide` text,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=latin1
		");

$this->endSetup();