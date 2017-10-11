SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `timezones`
--

CREATE TABLE IF NOT EXISTS `timezones` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(35) collate utf8_bin NOT NULL,
  `country_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_zone_name` (`name`)
) ENGINE=InnoDB;

--
-- Dumping data for table `timezones`
--

INSERT INTO `timezones` (`id`, `name`, `country_id`) VALUES
(1, 'Europe/Andorra', 6),
(2, 'Asia/Dubai', 231),
(3, 'Asia/Kabul', 1),
(4, 'America/Antigua', 10),
(5, 'America/Anguilla', 8),
(6, 'Europe/Tirane', 3),
(7, 'Asia/Yerevan', 12),
(8, 'Africa/Luanda', 7),
(9, 'Antarctica/McMurdo', 9),
(10, 'Antarctica/South_Pole', 9),
(11, 'Antarctica/Rothera', 9),
(12, 'Antarctica/Palmer', 9),
(13, 'Antarctica/Mawson', 9),
(14, 'Antarctica/Davis', 9),
(15, 'Antarctica/Casey', 9),
(16, 'Antarctica/Vostok', 9),
(17, 'Antarctica/DumontDUrville', 9),
(18, 'Antarctica/Syowa', 9),
(19, 'Antarctica/Macquarie', 9),
(20, 'America/Argentina/Buenos_Aires', 11),
(21, 'America/Argentina/Cordoba', 11),
(22, 'America/Argentina/Salta', 11),
(23, 'America/Argentina/Jujuy', 11),
(24, 'America/Argentina/Tucuman', 11),
(25, 'America/Argentina/Catamarca', 11),
(26, 'America/Argentina/La_Rioja', 11),
(27, 'America/Argentina/San_Juan', 11),
(28, 'America/Argentina/Mendoza', 11),
(29, 'America/Argentina/San_Luis', 11),
(30, 'America/Argentina/Rio_Gallegos', 11),
(31, 'America/Argentina/Ushuaia', 11),
(32, 'Pacific/Pago_Pago', 5),
(33, 'Europe/Vienna', 15),
(34, 'Australia/Lord_Howe', 14),
(35, 'Australia/Hobart', 14),
(36, 'Australia/Currie', 14),
(37, 'Australia/Melbourne', 14),
(38, 'Australia/Sydney', 14),
(39, 'Australia/Broken_Hill', 14),
(40, 'Australia/Brisbane', 14),
(41, 'Australia/Lindeman', 14),
(42, 'Australia/Adelaide', 14),
(43, 'Australia/Darwin', 14),
(44, 'Australia/Perth', 14),
(45, 'Australia/Eucla', 14),
(46, 'America/Aruba', 13),
(47, 'Europe/Mariehamn', 2),
(48, 'Asia/Baku', 16),
(49, 'Europe/Sarajevo', 29),
(50, 'America/Barbados', 20),
(51, 'Asia/Dhaka', 19),
(52, 'Europe/Brussels', 22),
(53, 'Africa/Ouagadougou', 34),
(54, 'Europe/Sofia', 33),
(55, 'Asia/Bahrain', 18),
(56, 'Africa/Bujumbura', 35),
(57, 'Africa/Porto-Novo', 24),
(58, 'America/St_Barthelemy', 182),
(59, 'Atlantic/Bermuda', 25),
(60, 'Asia/Brunei', 32),
(61, 'America/La_Paz', 27),
(62, 'America/Kralendijk', 28),
(63, 'America/Noronha', 31),
(64, 'America/Belem', 31),
(65, 'America/Fortaleza', 31),
(66, 'America/Recife', 31),
(67, 'America/Araguaina', 31),
(68, 'America/Maceio', 31),
(69, 'America/Bahia', 31),
(70, 'America/Sao_Paulo', 31),
(71, 'America/Campo_Grande', 31),
(72, 'America/Cuiaba', 31),
(73, 'America/Santarem', 31),
(74, 'America/Porto_Velho', 31),
(75, 'America/Boa_Vista', 31),
(76, 'America/Manaus', 31),
(77, 'America/Eirunepe', 31),
(78, 'America/Rio_Branco', 31),
(79, 'America/Nassau', 17),
(80, 'Asia/Thimphu', 26),
(81, 'Africa/Gaborone', 30),
(82, 'Europe/Minsk', 21),
(83, 'America/Belize', 23),
(84, 'America/St_Johns', 38),
(85, 'America/Halifax', 38),
(86, 'America/Glace_Bay', 38),
(87, 'America/Moncton', 38),
(88, 'America/Goose_Bay', 38),
(89, 'America/Blanc-Sablon', 38),
(90, 'America/Montreal', 38),
(91, 'America/Toronto', 38),
(92, 'America/Nipigon', 38),
(93, 'America/Thunder_Bay', 38),
(94, 'America/Iqaluit', 38),
(95, 'America/Pangnirtung', 38),
(96, 'America/Resolute', 38),
(97, 'America/Atikokan', 38),
(98, 'America/Rankin_Inlet', 38),
(99, 'America/Winnipeg', 38),
(100, 'America/Rainy_River', 38),
(101, 'America/Regina', 38),
(102, 'America/Swift_Current', 38),
(103, 'America/Edmonton', 38),
(104, 'America/Cambridge_Bay', 38),
(105, 'America/Yellowknife', 38),
(106, 'America/Inuvik', 38),
(107, 'America/Creston', 38),
(108, 'America/Dawson_Creek', 38),
(109, 'America/Vancouver', 38),
(110, 'America/Whitehorse', 38),
(111, 'America/Dawson', 38),
(112, 'Indian/Cocos', 46),
(113, 'Africa/Kinshasa', 50),
(114, 'Africa/Lubumbashi', 50),
(115, 'Africa/Bangui', 41),
(116, 'Africa/Brazzaville', 49),
(117, 'Europe/Zurich', 213),
(118, 'Africa/Abidjan', 53),
(119, 'Pacific/Rarotonga', 51),
(120, 'America/Santiago', 43),
(121, 'Pacific/Easter', 43),
(122, 'Africa/Douala', 37),
(123, 'Asia/Shanghai', 44),
(124, 'Asia/Harbin', 44),
(125, 'Asia/Chongqing', 44),
(126, 'Asia/Urumqi', 44),
(127, 'Asia/Kashgar', 44),
(128, 'America/Bogota', 47),
(129, 'America/Costa_Rica', 52),
(130, 'America/Havana', 55),
(131, 'Atlantic/Cape_Verde', 39),
(132, 'America/Curacao', 56),
(133, 'Indian/Christmas', 45),
(134, 'Asia/Nicosia', 57),
(135, 'Europe/Prague', 58),
(136, 'Europe/Berlin', 81),
(137, 'Africa/Djibouti', 60),
(138, 'Europe/Copenhagen', 59),
(139, 'America/Dominica', 61),
(140, 'America/Santo_Domingo', 62),
(141, 'Africa/Algiers', 4),
(142, 'America/Guayaquil', 63),
(143, 'Pacific/Galapagos', 63),
(144, 'Europe/Tallinn', 68),
(145, 'Africa/Cairo', 64),
(146, 'Africa/El_Aaiun', 242),
(147, 'Africa/Asmara', 67),
(148, 'Europe/Madrid', 206),
(149, 'Africa/Ceuta', 206),
(150, 'Atlantic/Canary', 206),
(151, 'Africa/Addis_Ababa', 69),
(152, 'Europe/Helsinki', 73),
(153, 'Pacific/Fiji', 72),
(154, 'Atlantic/Stanley', 70),
(155, 'Pacific/Chuuk', 143),
(156, 'Pacific/Pohnpei', 143),
(157, 'Pacific/Kosrae', 143),
(158, 'Atlantic/Faroe', 71),
(159, 'Europe/Paris', 74),
(160, 'Africa/Libreville', 78),
(161, 'Europe/London', 232),
(162, 'America/Grenada', 86),
(163, 'Asia/Tbilisi', 80),
(164, 'America/Cayenne', 75),
(165, 'Europe/Guernsey', 90),
(166, 'Africa/Accra', 82),
(167, 'Europe/Gibraltar', 83),
(168, 'America/Godthab', 85),
(169, 'America/Danmarkshavn', 85),
(170, 'America/Scoresbysund', 85),
(171, 'America/Thule', 85),
(172, 'Africa/Banjul', 79),
(173, 'Africa/Conakry', 91),
(174, 'America/Guadeloupe', 87),
(175, 'Africa/Malabo', 66),
(176, 'Europe/Athens', 84),
(177, 'Atlantic/South_Georgia', 204),
(178, 'America/Guatemala', 89),
(179, 'Pacific/Guam', 88),
(180, 'Africa/Bissau', 92),
(181, 'America/Guyana', 93),
(182, 'Asia/Hong_Kong', 97),
(183, 'America/Tegucigalpa', 96),
(184, 'Europe/Zagreb', 54),
(185, 'America/Port-au-Prince', 94),
(186, 'Europe/Budapest', 98),
(187, 'Asia/Jakarta', 101),
(188, 'Asia/Pontianak', 101),
(189, 'Asia/Makassar', 101),
(190, 'Asia/Jayapura', 101),
(191, 'Europe/Dublin', 104),
(192, 'Asia/Jerusalem', 106),
(193, 'Europe/Isle_of_Man', 105),
(194, 'Asia/Calcutta', 100),
(195, 'Indian/Chagos', 0),
(196, 'Asia/Baghdad', 103),
(197, 'Asia/Tehran', 102),
(198, 'Atlantic/Reykjavik', 99),
(199, 'Europe/Rome', 107),
(200, 'Europe/Jersey', 110),
(201, 'America/Jamaica', 108),
(202, 'Asia/Amman', 111),
(203, 'Asia/Tokyo', 109),
(204, 'Africa/Nairobi', 113),
(205, 'Asia/Bishkek', 119),
(206, 'Asia/Phnom_Penh', 36),
(207, 'Pacific/Tarawa', 114),
(208, 'Pacific/Enderbury', 114),
(209, 'Pacific/Kiritimati', 114),
(210, 'Indian/Comoro', 48),
(211, 'America/St_Kitts', 184),
(212, 'Asia/Pyongyang', 115),
(213, 'Asia/Seoul', 116),
(214, 'Asia/Kuwait', 118),
(215, 'America/Cayman', 40),
(216, 'Asia/Almaty', 112),
(217, 'Asia/Qyzylorda', 112),
(218, 'Asia/Aqtobe', 112),
(219, 'Asia/Aqtau', 112),
(220, 'Asia/Oral', 112),
(221, 'Asia/Vientiane', 120),
(222, 'Asia/Beirut', 122),
(223, 'America/St_Lucia', 185),
(224, 'Europe/Vaduz', 126),
(225, 'Asia/Colombo', 207),
(226, 'Africa/Monrovia', 124),
(227, 'Africa/Maseru', 123),
(228, 'Europe/Vilnius', 127),
(229, 'Europe/Luxembourg', 128),
(230, 'Europe/Riga', 121),
(231, 'Africa/Tripoli', 125),
(232, 'Africa/Casablanca', 149),
(233, 'Europe/Monaco', 145),
(234, 'Europe/Chisinau', 144),
(235, 'Europe/Podgorica', 147),
(236, 'America/Marigot', 186),
(237, 'Indian/Antananarivo', 131),
(238, 'Pacific/Majuro', 137),
(239, 'Pacific/Kwajalein', 137),
(240, 'Europe/Skopje', 130),
(241, 'Africa/Bamako', 135),
(242, 'Asia/Rangoon', 151),
(243, 'Asia/Ulaanbaatar', 146),
(244, 'Asia/Hovd', 146),
(245, 'Asia/Choibalsan', 146),
(246, 'Asia/Macau', 129),
(247, 'Pacific/Saipan', 162),
(248, 'America/Martinique', 138),
(249, 'Africa/Nouakchott', 139),
(250, 'America/Montserrat', 148),
(251, 'Europe/Malta', 136),
(252, 'Indian/Mauritius', 140),
(253, 'Indian/Maldives', 134),
(254, 'Africa/Blantyre', 132),
(255, 'America/Mexico_City', 142),
(256, 'America/Cancun', 142),
(257, 'America/Merida', 142),
(258, 'America/Monterrey', 142),
(259, 'America/Matamoros', 142),
(260, 'America/Mazatlan', 142),
(261, 'America/Chihuahua', 142),
(262, 'America/Ojinaga', 142),
(263, 'America/Hermosillo', 142),
(264, 'America/Tijuana', 142),
(265, 'America/Santa_Isabel', 142),
(266, 'America/Bahia_Banderas', 142),
(267, 'Asia/Kuala_Lumpur', 133),
(268, 'Asia/Kuching', 133),
(269, 'Africa/Maputo', 150),
(270, 'Africa/Windhoek', 152),
(271, 'Pacific/Noumea', 155),
(272, 'Africa/Niamey', 158),
(273, 'Pacific/Norfolk', 161),
(274, 'Africa/Lagos', 159),
(275, 'America/Managua', 157),
(276, 'Europe/Amsterdam', 154),
(277, 'Europe/Oslo', 163),
(278, 'Asia/Kathmandu', 153),
(279, 'Pacific/Nauru', 0),
(280, 'Pacific/Niue', 160),
(281, 'Pacific/Auckland', 156),
(282, 'Pacific/Chatham', 156),
(283, 'Asia/Muscat', 164),
(284, 'America/Panama', 168),
(285, 'America/Lima', 171),
(286, 'Pacific/Tahiti', 76),
(287, 'Pacific/Marquesas', 76),
(288, 'Pacific/Gambier', 76),
(289, 'Pacific/Port_Moresby', 169),
(290, 'Asia/Manila', 172),
(291, 'Asia/Karachi', 165),
(292, 'Europe/Warsaw', 174),
(293, 'America/Miquelon', 187),
(294, 'Pacific/Pitcairn', 173),
(295, 'America/Puerto_Rico', 176),
(296, 'Asia/Gaza', 167),
(297, 'Asia/Hebron', 167),
(298, 'Europe/Lisbon', 175),
(299, 'Atlantic/Madeira', 175),
(300, 'Atlantic/Azores', 175),
(301, 'Pacific/Palau', 166),
(302, 'America/Asuncion', 170),
(303, 'Asia/Qatar', 177),
(304, 'Indian/Reunion', 178),
(305, 'Europe/Bucharest', 179),
(306, 'Europe/Belgrade', 194),
(307, 'Europe/Kaliningrad', 180),
(308, 'Europe/Moscow', 180),
(309, 'Europe/Volgograd', 180),
(310, 'Europe/Samara', 180),
(311, 'Asia/Yekaterinburg', 180),
(312, 'Asia/Omsk', 180),
(313, 'Asia/Novosibirsk', 180),
(314, 'Asia/Novokuznetsk', 180),
(315, 'Asia/Krasnoyarsk', 180),
(316, 'Asia/Irkutsk', 180),
(317, 'Asia/Yakutsk', 180),
(318, 'Asia/Vladivostok', 180),
(319, 'Asia/Sakhalin', 180),
(320, 'Asia/Magadan', 180),
(321, 'Asia/Kamchatka', 180),
(322, 'Asia/Anadyr', 180),
(323, 'Africa/Kigali', 181),
(324, 'Asia/Riyadh', 192),
(325, 'Pacific/Guadalcanal', 201),
(326, 'Indian/Mahe', 195),
(327, 'Africa/Khartoum', 208),
(328, 'Europe/Stockholm', 212),
(329, 'Asia/Singapore', 197),
(330, 'Atlantic/St_Helena', 183),
(331, 'Europe/Ljubljana', 200),
(332, 'Arctic/Longyearbyen', 210),
(333, 'Europe/Bratislava', 199),
(334, 'Africa/Freetown', 196),
(335, 'Europe/San_Marino', 190),
(336, 'Africa/Dakar', 193),
(337, 'Africa/Mogadishu', 202),
(338, 'America/Paramaribo', 209),
(339, 'Africa/Juba', 205),
(340, 'Africa/Sao_Tome', 191),
(341, 'America/El_Salvador', 65),
(342, 'America/Lower_Princes', 198),
(343, 'Asia/Damascus', 214),
(344, 'Africa/Mbabane', 211),
(345, 'America/Grand_Turk', 227),
(346, 'Africa/Ndjamena', 42),
(347, 'Indian/Kerguelen', 77),
(348, 'Africa/Lome', 220),
(349, 'Asia/Bangkok', 218),
(350, 'Asia/Dushanbe', 216),
(351, 'Pacific/Fakaofo', 221),
(352, 'Asia/Dili', 219),
(353, 'Asia/Ashgabat', 226),
(354, 'Africa/Tunis', 224),
(355, 'Pacific/Tongatapu', 222),
(356, 'Europe/Istanbul', 225),
(357, 'America/Port_of_Spain', 223),
(358, 'Pacific/Funafuti', 228),
(359, 'Asia/Taipei', 215),
(360, 'Africa/Dar_es_Salaam', 217),
(361, 'Europe/Kiev', 230),
(362, 'Europe/Uzhgorod', 230),
(363, 'Europe/Zaporozhye', 230),
(364, 'Europe/Simferopol', 230),
(365, 'Africa/Kampala', 229),
(366, 'Pacific/Johnston', 0),
(367, 'Pacific/Midway', 0),
(368, 'Pacific/Wake', 0),
(369, 'America/New_York', 233),
(370, 'America/Detroit', 233),
(371, 'America/Kentucky/Louisville', 233),
(372, 'America/Kentucky/Monticello', 233),
(373, 'America/Indiana/Indianapolis', 233),
(374, 'America/Indiana/Vincennes', 233),
(375, 'America/Indiana/Winamac', 233),
(376, 'America/Indiana/Marengo', 233),
(377, 'America/Indiana/Petersburg', 233),
(378, 'America/Indiana/Vevay', 233),
(379, 'America/Chicago', 233),
(380, 'America/Indiana/Tell_City', 233),
(381, 'America/Indiana/Knox', 233),
(382, 'America/Menominee', 233),
(383, 'America/North_Dakota/Center', 233),
(384, 'America/North_Dakota/New_Salem', 233),
(385, 'America/North_Dakota/Beulah', 233),
(386, 'America/Denver', 233),
(387, 'America/Boise', 233),
(388, 'America/Shiprock', 233),
(389, 'America/Phoenix', 233),
(390, 'America/Los_Angeles', 233),
(391, 'America/Anchorage', 233),
(392, 'America/Juneau', 233),
(393, 'America/Sitka', 233),
(394, 'America/Yakutat', 233),
(395, 'America/Nome', 233),
(396, 'America/Adak', 233),
(397, 'America/Metlakatla', 233),
(398, 'Pacific/Honolulu', 233),
(399, 'America/Montevideo', 234),
(400, 'Asia/Samarkand', 235),
(401, 'Asia/Tashkent', 235),
(402, 'Europe/Vatican', 95),
(403, 'America/St_Vincent', 188),
(404, 'America/Caracas', 237),
(405, 'America/Tortola', 239),
(406, 'America/St_Thomas', 240),
(407, 'Asia/Ho_Chi_Minh', 238),
(408, 'Pacific/Efate', 236),
(409, 'Pacific/Wallis', 241),
(410, 'Pacific/Apia', 189),
(411, 'Asia/Aden', 243),
(412, 'Indian/Mayotte', 141),
(413, 'Africa/Johannesburg', 203),
(414, 'Africa/Lusaka', 244),
(415, 'Africa/Harare', 245),
(416, 'Africa/Asmera', 67),
(417, 'Africa/Timbuktu', 135),
(418, 'America/Argentina/ComodRivadavia', 11),
(419, 'America/Atka', 233),
(420, 'America/Buenos_Aires', 11),
(421, 'America/Catamarca', 11),
(422, 'America/Coral_Harbour', 38),
(423, 'America/Cordoba', 11),
(424, 'America/Ensenada', 142),
(425, 'America/Fort_Wayne', 233),
(426, 'America/Indianapolis', 233),
(427, 'America/Jujuy', 11),
(428, 'America/Knox_IN', 233),
(429, 'America/Louisville', 233),
(430, 'America/Mendoza', 11),
(431, 'America/Porto_Acre', 31),
(432, 'America/Rosario', 11),
(433, 'America/Virgin', 240),
(434, 'Asia/Ashkhabad', 226),
(435, 'Asia/Chungking', 44),
(436, 'Asia/Dacca', 19),
(437, 'Asia/Istanbul', 57),
(438, 'Asia/Katmandu', 153),
(439, 'Asia/Macao', 129),
(440, 'Asia/Saigon', 238),
(441, 'Asia/Tel_Aviv', 106),
(442, 'Asia/Thimbu', 26),
(443, 'Asia/Ujung_Pandang', 101),
(444, 'Asia/Ulan_Bator', 146),
(445, 'Atlantic/Faeroe', 71),
(446, 'Atlantic/Jan_Mayen', 163),
(447, 'Australia/ACT', 14),
(448, 'Australia/Canberra', 14),
(449, 'Australia/LHI', 14),
(450, 'Australia/North', 14),
(451, 'Australia/NSW', 14),
(452, 'Australia/Queensland', 14),
(453, 'Australia/South', 14),
(454, 'Australia/Tasmania', 14),
(455, 'Australia/Victoria', 14),
(456, 'Australia/West', 14),
(457, 'Australia/Yancowinna', 14),
(458, 'Europe/Belfast', 232),
(459, 'Europe/Nicosia', 84),
(460, 'Europe/Tiraspol', 84),
(461, 'Pacific/Ponape', 143),
(462, 'Pacific/Samoa', 0),
(463, 'Pacific/Truk', 143),
(464, 'Pacific/Yap', 143);

