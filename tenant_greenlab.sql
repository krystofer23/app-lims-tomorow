/*
 Navicat Premium Data Transfer

 Source Server         : LocalHost
 Source Server Type    : MySQL
 Source Server Version : 80030 (8.0.30)
 Source Host           : localhost:3306
 Source Schema         : tenant_greenlab

 Target Server Type    : MySQL
 Target Server Version : 80030 (8.0.30)
 File Encoding         : 65001

 Date: 16/04/2026 07:12:09
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for chain_custody
-- ----------------------------
DROP TABLE IF EXISTS `chain_custody`;
CREATE TABLE `chain_custody`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NULL DEFAULT NULL,
  `application_id` bigint UNSIGNED NULL DEFAULT NULL,
  `order_id` bigint UNSIGNED NULL DEFAULT NULL,
  `os` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `content` json NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `chain_custody_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `chain_custody_application_id_foreign`(`application_id` ASC) USING BTREE,
  INDEX `chain_custody_order_id_foreign`(`order_id` ASC) USING BTREE,
  CONSTRAINT `chain_custody_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `companies` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `chain_custody_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `chain_custody_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `order_service` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of chain_custody
-- ----------------------------

-- ----------------------------
-- Table structure for companies
-- ----------------------------
DROP TABLE IF EXISTS `companies`;
CREATE TABLE `companies`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ruc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `business_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `direction` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `activity` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `state` tinyint(1) NOT NULL DEFAULT 0,
  `is_supplier` tinyint(1) NOT NULL DEFAULT 0,
  `is_partner` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of companies
-- ----------------------------
INSERT INTO `companies` VALUES (1, '10749923887', 'MUGUERZA SALAZAR KRYSTOFER LUIS', 'Av. Peru 4837', 'Finanzas', 1, 0, 0, '2026-04-11 18:17:15', '2026-04-11 23:17:15', NULL);

-- ----------------------------
-- Table structure for conditions
-- ----------------------------
DROP TABLE IF EXISTS `conditions`;
CREATE TABLE `conditions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of conditions
-- ----------------------------
INSERT INTO `conditions` VALUES (19, 'ACREDITADO ANTE INACAL', NULL, '2026-04-11 12:57:31', '2026-04-11 17:57:31', NULL);
INSERT INTO `conditions` VALUES (20, 'ACREDITADO ANTE IAS', NULL, '2026-04-11 12:57:31', '2026-04-11 17:57:31', NULL);
INSERT INTO `conditions` VALUES (21, 'ACREDITADO ANTE INACAL(*)', NULL, '2026-04-11 12:57:31', '2026-04-11 17:57:31', NULL);
INSERT INTO `conditions` VALUES (22, 'NO ACREDITADO', NULL, '2026-04-11 12:57:31', '2026-04-11 17:57:31', NULL);
INSERT INTO `conditions` VALUES (23, 'ACREDITADO ANTE IAS(*)', NULL, '2026-04-11 12:57:31', '2026-04-11 17:57:31', NULL);
INSERT INTO `conditions` VALUES (24, 'SUBCONTRATADO IAS(*)', NULL, '2026-04-11 12:57:31', '2026-04-11 17:57:31', NULL);
INSERT INTO `conditions` VALUES (25, 'INACAL', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `conditions` VALUES (26, 'IAS(*)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `conditions` VALUES (27, 'IAS', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);

-- ----------------------------
-- Table structure for contact_companies
-- ----------------------------
DROP TABLE IF EXISTS `contact_companies`;
CREATE TABLE `contact_companies`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `is_collection` tinyint(1) NOT NULL DEFAULT 0,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `company_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `contact_companies_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `contact_companies_user_id_foreign`(`user_id` ASC) USING BTREE,
  CONSTRAINT `contact_companies_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `contact_companies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of contact_companies
-- ----------------------------
INSERT INTO `contact_companies` VALUES (1, 2, '960787167', 'mkrystofer2021@gmail.com', 1, 1, 'Gerente General', 1, '2026-04-11 18:17:16', '2026-04-11 23:17:16', NULL);

-- ----------------------------
-- Table structure for essays
-- ----------------------------
DROP TABLE IF EXISTS `essays`;
CREATE TABLE `essays`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `lcm` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `units_measurement_id` bigint UNSIGNED NULL DEFAULT NULL,
  `condition_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `essays_condition_id_foreign`(`condition_id` ASC) USING BTREE,
  INDEX `essays_units_measurement_id_foreign`(`units_measurement_id` ASC) USING BTREE,
  CONSTRAINT `essays_condition_id_foreign` FOREIGN KEY (`condition_id`) REFERENCES `conditions` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `essays_units_measurement_id_foreign` FOREIGN KEY (`units_measurement_id`) REFERENCES `units_measurement` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 891 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of essays
-- ----------------------------
INSERT INTO `essays` VALUES (409, 'Material particulado PM10 (Alto volumen)', '1,5', 79, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (410, 'Material Particulado - PM 2.5 (Bajo Volúmen)', '3', 79, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (411, 'Material particulado - PM10 (Bajo Volúmen)', '3', 79, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (412, 'Material particulado - PM 2.5 (Alto  Volúmen)', '1,5', 79, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (413, '- Aluminio', '0,0301', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (414, '- Arsénico', '0,0133', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (415, '- Boro', '0,0149', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (416, '- Bario', '0,0016', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (417, '- Berilio', '0,0008', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (418, '- Calcio', '0,0502', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (419, '- Cadmio', '0,0023', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (420, '- Cerio', '0,0233', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (421, '- Cobalto', '0,0070', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (422, '- Cromo', '0,0055', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (423, '- Cobre', '0,0047', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (424, '- Hierro', '0,0168', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (425, '- Potasio', '0,0888', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (426, '- Litio', '0,0015', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (427, '- Magnesio', '0,0114', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (428, '- Manganeso', '0,0019', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (429, '- Molibdeno', '0,0034', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (430, '- Niquel', '0,0063', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (431, '- Fósforo', '0,0460', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (432, '- Plomo', '0,0156', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (433, '- Antimonio', '0,0109', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (434, '- Selenio', '0,0725', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (435, '- Estaño', '0,0207', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (436, '- Estroncio', '0,0004', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (437, '- Titanio', '0,0015', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (438, '- Talio', '0,0725', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (439, '-Vanadio', '0,0032', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (440, '- Zinc', '0,0586', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (441, '- Sílice', '0,1939', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (442, '- Aluminio', '0,0400', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (443, '- Arsénico', '0,0180', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (444, '- Boro', '0,0200', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (445, '- Bario', '0,0020', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (446, '- Berilio', '0,0010', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (447, '- Calcio', '0,0667', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (448, '- Cadmio', '0,0031', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (449, '- Cerio', '0,0309', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (450, '- Cobalto', '0,0093', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (451, '- Cromo', '0,0073', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (452, '- Cobre', '0,0063', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (453, '- Hierro', '0,0223', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (454, '- Mercurio', '0,0363', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (455, '- Potasio', '0,1179', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (456, '- Litio', '0,0019', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (457, '- Magnesio', '0,0151', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (458, '- Manganeso', '0,0025', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (459, '- Molibdeno', '0,0045', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (460, '- Niquel', '0,0083', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (461, '- Fósforo', '0,0611', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (462, '- Plomo', '0,0207', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (463, '- Antimonio', '0,0145', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (464, '- Selenio', '0,0963', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (465, '- Estaño', '0,0274', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (466, '- Estroncio', '0,0007', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (467, '- Titanio', '0,0010', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (468, '- Talio', '0,0997', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (469, '-Vanadio', '0,0050', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (470, '- Zinc', '0,0712', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (471, '- Sílice', '0,3176', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (472, 'Metales en Filtro PM10  Alto Volumen                                            ( Plomo)', '0,0207', 80, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (473, 'Metales en Filtro PM10  Alto Volumen                                            ( Plomo)', '0,0207', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (474, '- Aluminio', '0,0400', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (475, '- Arsénico', '0,0180', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (476, '- Boro', '0,0200', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (477, '- Bario', '0,0020', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (478, '- Berilio', '0,0010', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (479, '- Calcio', '0,0667', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (480, '- Cadmio', '0,0031', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (481, '- Cerio', '0,0309', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (482, '- Cobalto', '0,0093', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (483, '- Cromo', '0,0073', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (484, '- Cobre', '0,0063', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (485, '- Hierro', '0,0223', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (486, '- Mercurio', '0,0363', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (487, '- Potasio', '0,1179', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (488, '- Litio', '0,0019', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (489, '- Magnesio', '0,0151', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (490, '- Manganeso', '0,0025', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (491, '- Molibdeno', '0,0045', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (492, '- Sodio', '0,1000', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (493, '- Niquel', '0,0083', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (494, '- Fósforo', '0,0611', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (495, '- Plomo', '0,0207', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (496, '- Antimonio', '0,0145', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (497, '- Selenio', '0,0963', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (498, '- Sílice', '0,3176', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (499, '- Estaño', '0,0274', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (500, '- Estroncio', '0,0007', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (501, '- Titanio', '0,0010', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (502, '- Talio', '0,0997', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (503, '-Vanadio', '0,0050', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (504, '- Zinc', '0,0712', 80, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (505, 'Temperatura  Ambiental', '0,5', 81, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (506, 'Humedad Ambiental', '3', 82, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (507, 'Presión Ambiental', '0,5', 83, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (508, 'Velocidad del viento', '0,0', 84, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (509, 'Dirección del viento', '1', 85, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (510, 'Precipitación', '0,0', 86, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (511, 'Dióxido de Azufre (SO2)\n(muestreo 24 horas)', '13', 79, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (512, 'Dióxido de Nitrógeno (NO2) \n(muestreo 1 hora)', '4', 79, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (513, 'Monóxido de Carbono (CO)\n (muestreo 8 horas)', '561', 79, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (514, 'Sulfuro de Hidrógeno (H2S) \n (muestreo 24 horas)', '2,6', 79, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (515, 'Ozono (O3)\n (muestreo 8 horas)', '1,00', 79, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (516, 'Nitrogen Oxides (NOx) \n(muestreo 1 hora)', '4', 79, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (517, 'Benceno', '0,800', 79, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (518, 'Mercurio Gaseoso Total', '0.02343', 79, 21, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (519, 'Benzene (C6H6)\nBenceno pasivo', '0.174', 79, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (520, 'Hidrocarburos Totales expresados como Hexano', '0,03', 87, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (521, 'Dióxido de Azufre (SO2)\n(Método Automático)', '1,1 (z)', 79, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (522, 'Dióxido de Nitrógeno (NO2)\n(Método Automático)', '0,9 (z)', 79, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (523, 'Monóxido de Carbono (CO) \n(Método Automático)', '76 (z)', 79, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (524, 'Sulfuro de Hidrógeno (H2S) \n(Método Automático)', '0,5 (z)', 79, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (525, 'Ozono (O3)  \n(Método Automático)', '0.8 (z)', 79, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (526, 'Nitrogen Oxides (NOx)\n(Método Automático)', '0.9 (z)', 79, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (527, 'Nitric Oxide (NO)\n(Método Automático)', '0.9 (z)', 79, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (528, 'Mercurio Gaseoso Total\n(Método Automático)', '0.000036 (z)', 79, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (529, 'Conductividad (campo)', '0.05', 88, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (530, 'Oxígeno Disuelto (campo)', '0.01', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (531, 'pH (campo)', '0.01', 90, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (532, 'Temperatura (campo)', '0.1', 81, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (533, 'Turbidez (campo)', '0.2', 91, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (534, 'Nivel Freático (campo)\nWater Level (in situ)', '0,1 (z)', 86, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (535, 'Salinidad (campo)', '-', 92, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (536, 'Caudal (campo)\nCorrentómetro', '0.001(z)', 93, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (537, 'Caudal (campo)\n flotador', '0,0004', 93, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (538, 'Caudal (campo)\nultrasónicos', '0,00007 (z)', 93, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (539, 'Caudal (campo)volumetrico', '0,00001', 93, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (540, 'Potential REDOX (Campo)', '0,1 (z)', 94, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (541, 'Floating Materials of Anthropogenic Origin\n(campo)', '-', 95, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (542, 'Cloro Total (Campo)', '0,02 (z)', 89, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (543, 'Cloro Residual Libre (Campo)', '0,02 (z)', 89, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (544, 'Olor', '', 89, 22, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (545, 'Sabor', '', 89, 22, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (546, 'Aceites y Grasas', '0.4', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (547, 'Acidez', '1', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (548, 'Alcalinidad Total', '1', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (549, 'Bicarbonatos', '1', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (550, 'Carbonatos', '1', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (551, 'Surfactantes Aniónicos (SAAM)', '0.01', 96, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (552, 'Cianuro Total', '0.025', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (553, 'Cianuro Libre', '0.002', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (554, 'Cianuro Wad', '0.025', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (555, 'Cloruro', '3', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (556, 'Cromo Hexavalente (VI)', '0.005', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (557, 'Dureza Cálcica', '1.2', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (558, 'Dureza Total', '1.1', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (559, 'Fluoruro', '0.05', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (560, 'Fosfato', '0.01', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (561, 'Nitrato', '0.08', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (562, 'Nitrito', '0.01', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (563, 'Sulfato', '3', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (564, 'Nitrógeno Amoniacal', '0.01', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (565, 'Total Nitrogen', '0.01', 89, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (566, 'Demanda Química de Oxigeno - (DQO)', '5', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (567, 'Demanda Bioquímica de Oxígeno - (DBO)', '1', 89, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (568, 'Demanda Química de Oxígeno - (DQO)', '10', 97, 20, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (569, 'Fenoles', '0.003', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (570, 'Fósforo Total', '0.01', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (571, 'N-Nitrates+N-Nitrites (NO3-N\nNO2-N)', '0.063', 89, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (572, 'Amoniaco', '0.01', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (573, 'Sólidos Sedimentables', '1', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (574, 'Sólidos Totales', '3', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (575, 'Sólidos Totales Disueltos', '3', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (576, 'Sólidos Totales Suspendidos', '3', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (577, 'Sulfuro', '0.0021', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (578, 'Amonio', '0.01', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (579, 'Hidrocarburos Totales de Petróleo (TPH).                                        Rango (C10 - C40)', '0.15', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (580, 'Hidrocarburos Totales de Petróleo (AROMÁTICO)', '0.004', 89, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (581, 'Metales Totales (ICP-OES)                                        Plata, Aluminio, Arsénico, Bario, Berilio, Boro Calcio, Cadmio, Cobalto, Cromo, Cerio, Cobre, Hierro, Potasio, Litio, Magnesio,  Manganeso, Molibdeno, Mercurio, Sodio, Níquel,  Fosforo, Plomo, Antimonio, Selenio, Sílice (SiO2), Estroncio, Estaño, Titanio, Talio,  Vanadio, Zinc', '', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (582, 'Metales Disueltos (ICP-OES)                                            Plata, Aluminio, Arsénico, Bario, Berilio, Boro Calcio, Cadmio, Cobalto, Cromo, Cerio, Cobre, Hierro, Potasio, Litio, Magnesio,  Manganeso, Molibdeno, Mercurio, Sodio, Níquel,  Fosforo, Plomo, Antimonio, Selenio, Sílice (SiO2), Estroncio, Estaño, Titanio, Talio,  Vanadio, Zinc', '', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (583, 'Metales Totales (ICP-AES)                                    Uranio', '', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (584, 'Metales Disuelto (ICP-AES)                               Uranio', '', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (585, 'Metales Totales (ICP-AES)                                    Plata, Aluminio, Arsénico, Bario, Berilio, Boro Calcio, Cadmio, Cobalto, Cromo, Cerio, Cobre, Hierro, Potasio, Litio, Magnesio, Manganeso, Molibdeno, Mercurio, Sodio, Níquel, Fosforo, Plomo, Antimonio, Selenio, Sílice (SiO2), Estroncio, Estaño, Titanio, Talio, Vanadio, Zinc', '', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (586, 'Metales Disueltos (ICP-OES)                                    Plata, Aluminio, Arsénico, Bario, Berilio, Boro Calcio, Cadmio, Cobalto, Cromo, Cerio, Cobre, Hierro, Potasio, Litio, Magnesio, Manganeso, Molibdeno, Mercurio, Sodio, Níquel, Fosforo, Plomo, Antimonio, Selenio, Sílice (SiO2), Estroncio, Estaño, Titanio, Talio, Vanadio, Zinc', '', 89, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (587, 'Color', '5', 98, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (588, 'Nitrogeno Total', '', 89, 21, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (589, 'Trihalometanos\nBromoformo \nCloroformo\nDibromoclorometano  \nBromodiclorometano', '0,016', 89, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (590, 'VOCs\n1,1,1-Tricloroetano\n1,1-Dicloroeteno\n1,2 Dicloroetano\n1,2 Diclorobenceno\nHexaclorobutadieno\nTetracloroeteno\nTetracloruro de carbono\nTricloroeteno', '0.0009', 89, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (591, 'BTEX\nBenceno\nEtilbenceno\nTolueno\nXilenos', '0.001', 89, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (592, 'Hidrocarburos Aromáticos\nBenzo(a)pireno', '3.0E-5', 89, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (593, 'Hidrocarburos Aromáticos\nPentachlorophenol PCP', '0,0001', 89, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (594, 'Pesticidas Organofosforados\nDimethoate, Disulfoton, Famphur, Methyl Parathion; O, O, O-Triethylphosphorothioate, Parathion, Phorate, Sulfotep, Thionazin, Malathion', '0.0001', 89, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (595, 'Organochlorine Pesticides\nAldrín + Dieldrín\nClordano\nDicloro Difenil Tricloroetano\n(DDT)\nEndrin\nEndosulfan I\nHeptacloro + Heptacloro\nEpóxido\nLindano', '-', 89, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (596, 'Polynuclear Aromatic \nHydrocarbons\nPAHs:\nNaphthalene, Acenaphthylene, \nAcenaphthene, Fluorene,\nPhenanthrene, Anthracene,\nFluoranthene, Pyrene,\nBenz(a)anthracene, Chrysene,\nBenzo(b)fluoranthene,\nBenzo(k)fluoranthene, \nBenzo(a)pyrene, Indeno(1, 2, 3-\ncd)pyrene, Dibenz(a,h) anthracene,\nBenzo(g,h,i)perylene.', '', 89, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (597, 'Fenoles:\nFenol, 2-Clorofenol, o-Cresol, m Cresol, p-Cresol, 2-Nitrofenol; 2.4-\ndimetilfenol; 2,4-diclorofenol;\n2,6-Diclorofenol, 4-Cloro-3-\nMetilfenol; 2,4,6-triclorofenol;\n2,4,5-triclorofenol; 2,3,4,6-\nTetraclorofenol, 2-Metil-4,6-\ndinitrofenol, pentaclorofenol,\ndinoseb', '', 89, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (598, 'Carbamato\nAldicarb', '0.0005', 89, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (599, 'CIANOTOXINAS\nMicrocistina-LR', '0.0001', 89, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (600, 'Polychlorinated Biphenyls (PCB\'s)', '-', 89, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (601, 'Coliformes fecales o termotolerantes', '1.8', 99, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (602, 'Coliformes Totales', '1.8', 99, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (603, 'Escherichia Coli', '1.1', 99, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (604, 'Parásitos y Protozoarios (Huevos, Quistes y Ooquistes) PARASITES AND PROTOZOARIES \nPARASITES AND PROTOZOARIES \nNematodes\nAscaris sp.\nAncilostomídeos (Ancylostoma, \nNecator)\nEnterobius vermicularis\nTrichuris sp.\nToxocara sp.\nCapillaria sp.\nTrichostrongylus sp.\nCestodes\nDyphylidium sp.\nTaenia sp.\nHymenolepis diminuta\nHymenolepis nana\nHymenolepis sp.\nTrematodes\nFasciola hepatica\nParagonimus sp.\nSchistosoma sp.\nProtozoa - Amoebas and \nFlagellates\nEndolimax nana\nEntamoeba histolytica\nEntamoeba coli\nGiardia sp.\nIodamoeba sp.\nChilomastix sp.\nBlastocystis hominis\nBalantidium coli\nAcanthamoeba sp.\nCoccidia\nIsospora sp.', '', 100, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (605, 'Virus (Colifagos) (36.5 ± 2°C)', '1', 101, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (606, 'ALGAS; LARVAS U ORGANISMOS DE VIDA LIBRE (VIVOS \nORGANISMOS)\nPhytoplankton (Algae) + Zooplankton (Protozoa, rotifers, copepods and\nnematodes)', '1', 100, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (607, 'Huevos de hemintos\nHELMINTH EGGS\nNematodes\nAscaris sp.\nUncinarias (Ancylostoma, Strongylus, \nNecator)\nEnterobius vermicularis\nTrichuris sp.\nToxocara sp.\nCapillaria sp.\nTrichostrongylus sp.\nCestodes\nDyphylidium sp.\nTaenia sp.\nHymenolepis diminuta\nHymenolepis nana\nHymenolepis sp.\nTrematodes\nFasciola hepatica\nParagonimus sp.\nSchistosoma sp.\nAcantocephalus\nMacracanthorhynchus sp', '1', 100, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (608, 'Heterotrophic Bacteria', '1', 102, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (609, 'HELMINTH EGGS', '1', 100, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (610, 'Parásitos y Protozoarios', '1', 100, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (611, 'Vibrio cholerae Detection (35°-37°C)', '', 103, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (612, 'Enterococcus\nfaecalis/Enterococcus intestinalis\nNMP (35 ± 0.5°C)', '1.8', 99, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (613, 'Pseudomonas aeruginosa', '', NULL, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (614, 'Salmonella sp.', '', NULL, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (615, 'Staphylococcus aureus', '', NULL, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (616, 'Phytoplankton Quantitative', '1', 104, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (617, 'Zooplankton Quantitative', '1', 100, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (618, 'Macrozobentos / Macroinvertebrados', '', 105, 23, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (619, 'Cromo Hexavalente (VI)', '0.03', 106, 19, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `essays` VALUES (620, 'Cianuro Libre', '0.1', 106, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (621, 'Metales (ICP-AES)                                                                  Arsenico, Bario total , Cadmio, Cromo Plomo,Mercurio(Hg)', 'TABLA N°3', 106, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (622, 'Fracción de Hidrocarburos (TPH)                                                       F1 (C6 - C10)', '0.2', 106, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (623, 'Fracción de Hidrocarburos (TPH)                                                         F2 (C10-C28)', '15', 106, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (624, 'Fracción de Hidrocarburos (TPH)                                                     F3 (C28-C40)', '15.1', 106, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (625, 'BTEX -Hidrocarburos aromáticos volátiles', '', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (626, 'Benzene,', '0.012', 107, 23, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (627, 'Toluene,', '0.025', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (628, 'Ethylbenzene;', '0.029', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (629, 'Total Xylenes(m-p Xylene,o-Xylene)', '0.051', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (630, 'Tetrachloroethylene', '0.005', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (631, 'Trichloroethylene', '0.005', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (632, 'Benzo(a)pyrene,', '0,053', 107, 23, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (633, 'Naphthalene,', '0,011', 107, 23, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (634, 'PCB 28-2,2\',3,4,4\',5\'-Hexachlorobiphenyl,', '0,013', 107, 23, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (635, 'PCB 52-2,2\',3,4,4\',5,5\'-Heptachlorobiphenyl,', '0,020', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (636, 'PCB 101-2,2\',4,4\',5,5\'-Hexachlorobiphenyl,', '0,013', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (637, 'PCB 118-2,2\',5,5\'-Tetrachlorobiphenyl,', '0,013', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (638, 'PCB 138-2,2\',5-Trichlorobiphenyl,', '0,02', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (639, 'PCB 153-2,3\',4,4\',5-Pentachlorobiphenyl,', '0,00010', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (640, 'PCB 180-2,4,4\'-Trichlorobiphenyl,', '0,0010', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (641, 'Benzene,', '0,012', 107, 23, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (642, 'Toluene,', '0,025', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (643, 'Ethylbenzene;', '0,029', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (644, 'Xileno', '0,051', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (645, 'Naphtalene', '0,004', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (646, 'Fracción de Hidrocarburos (TPH)                                                       F1 (C5 - C10)', '0.2', 106, 23, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (647, '2-Chlorobiphenyl;', '0,027', 107, 23, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (648, '2,3-Dichlorobiphenyl;', '0,013', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (649, '2,2,5\'-Trichlorobiphenyl;', '0,020', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (650, '2,4\',5 -Trichlorobiphenyl;', '0,013', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (651, '2,2\',3,5\'-Tetrachlorobiphenyl;', '0,007', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (652, '2,2\',5,5\'-Tetrachlorobiphenyl;', '0,013', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (653, '2,3\',4,4\'-Tetrachlorobiphenyl;', '0,027', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (654, '2,2\',3,4,5-Pentachlorobiphenyl;', '0,013', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (655, '2,2\',4,5,5\'-Pentachlorobiphenyl;', '0,020', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (656, '2,3,3\',4\',6-Pentachlorobiphenyl;', '0,013', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (657, '2,2\',3,4,4\',5\'-Hexachlorobiphenyl;', '0,013', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (658, '2,2\',3,4,5,5\'-Hexachlorobiphenyl;', '0,027', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (659, '2,2\',3,5,5\',6-Hexachlorobiphenyl;', '0,020', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (660, '2,2\',4,4\',5,5\'-Hexachlorobiphenyl;', '0,013', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (661, '2,2\',3,3\',4,4\',5-Heptachlorobiphenyl;', '0,013', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (662, '2,2\',3,4,4\',5,5\'-Heptachlorobiphenyl;', '0,020', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (663, '2,2\',3,4,4\',5\',6-Heptachlorobiphenyl;', '0,013', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (664, '2,2\',3,4\',5,5\',6-Heptachlorobiphenyl;', '0,013', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (665, '2,2\',3,3\',4,4\',5,5\',6-Nonachlorobiphenyl;', '0,027', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (666, '2,4,4-Trichlorobiphenyl', '0,001', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (667, '2,3,4,4,5-Pentachlorobiphenyl', '0,0001', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (668, 'Total PCB\'s.', '0,027', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (669, 'Aldrin', '0,001', 107, 23, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (670, 'Endrin', '0,002', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (671, '4,4\'-DDD;', '0,001', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (672, 'Heptachloro', '0,002', NULL, NULL, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (673, 'METALES:_Aluminio, Arsenico, Plata, Bario, Berilio, Calcio, Cadmio, Cobalto, Cromo, Cobre, Hierro, Potasio, Magnesio, Manganeso, Mercurio, Molibdeno, Níquel, Sodio, Plomo, Antimonio, Talio, Vanadio, Zinc', 'TABLA N°3', 106, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (674, 'Ruido Ambiental (diurno y nocturno)', '0.1', 108, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (675, 'Ruido Ambiental (continuo)', '0.1', 108, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (676, 'Vibración Ambiental', '', 109, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (677, 'Vibración en edificios', '', 109, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (678, 'Radiaciones No Ionizantes\nIntensidad del campo eléctrico\nFuerza del campo magnético \nDensidad de potencia                                        \nDensidad de flujo magnético', '', 110, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (679, 'Partículas Totales o Inhalables', '0.1', 79, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (680, 'Partículas Respirables', '0.1', 79, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (681, 'Humos talicos \nMetales en el filtro\n(muestreo y análisis de campo)\nAluminio (Al), Antimonio (Sb), Arsénico (As), Bario (Ba), Berilio (Be), Cadmio (Cd), Cromo (Cr), Cobalto (Co), Cobre (Cu), Plomo (Pb), Manganeso (Mn), Molibdeno (Mo), Níquel (Ni), Selenio (Se), Plata (Ag), Talio (Tl), Torio (Th), Uranio (U), Vanadio (V), Zinc (Zn).', '', 87, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (682, 'Metals in Filter Mercury', '', 87, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (683, 'Determinación de Peso: Partículas Totales o Inhalables', '0.1', 111, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (684, 'Determinación de Peso: Partículas Respirables', '0.1', 111, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (685, 'Iluminación', '', 112, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (686, 'Occupational noise (sonometría)', '', 108, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (687, 'Occupational noise (dosimetría)', '', 108, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (688, 'Vibración Medición y evaluación de la exposición humana a vibraciones transmitidas manualmente.', '', 109, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (689, '- Monóxido de Carbono (CO).', '1,25', 113, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (690, '- Monóxido de Nitrógeno (NO).', '1,34', 113, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (691, '- Dioxido de Nitrogeno (NO2).', '0,21', 113, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (692, '- Oxígeno (O2).', '0-01', 82, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (693, '- Oxido de Nitrógeno (NOx).', '2,05', 113, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (694, 'Dióxido de Azufre (SO2)', '2,86', 113, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (695, 'Carbon Monoxide (CO)', '1,25', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (696, 'Material Particulado\n(Cálculo)', '-', 113, 24, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (697, '- Antimony (Sb) ²', '0,0145', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (698, '- Arsenic (As) ²', '0,0177', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (699, '- Barium (Ba) ²', '0,0021', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (700, '- Beryllium (Be) ²', '0,0011', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (701, '- Cadmium (Cd) ²', '0,0031', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (702, '- Chromium (Cr) ²', '0,0073', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (703, '- Cobalt (Co) ²', '0,0093', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (704, '- Copper (Cu) ²', '0,0063', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (705, '- Lead (Pb) ²', '0,0207', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (706, '- Manganese (Mn) ²', '0,0025', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (707, '- Mercury (Hg) ²', '0,0363', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (708, '- Nickel (Ni) ²', '0,0083', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (709, '- Phosphorus (P) ²', '0,0611', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (710, '- Selenium (Se) ²', '0,0963', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (711, '- Silver (Ag) ²', '0,0963', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (712, '- Thallium (Ti) ²', '0,04', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (713, '- Zinc (Zn) ²', '0,08', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (714, '- Vanadium (V) ²', '0,0043', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (715, '- Iron (Fe) ²', '0,0223', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (716, '- Tin (Sn) ²', '0,0274', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (717, '- Titanium (Ti) ²', '0,002', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (718, 'TRS (disulfuro de dimetilo, sulfuro de dimetilo, sulfuro de hidrógeno, metilmercaptano, sulfuro de carbonilo', '3,7', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (719, 'Dióxido de azufre en emisiones gaseosas\nSO2', '3,5', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (720, 'Ácido sulfúrico y dióxido de azufre en emisiones gaseosas', '', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (721, 'NOx en emisiones gaseosas\n(NO y NO2)', '', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (722, 'NOx en emisiones gaseosas\n(NO; NO2)', '', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (723, '- Benzene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (724, '- Trichloroethene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (725, '- Toluene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (726, '- Tetrachloroethene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (727, '- Chlorobenzene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (728, '- Ethylbenzene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (729, '- m-Xylene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (730, '- P-Xylene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (731, '- o-Xylene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (732, '- m,p-Xylene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (733, '- Styrene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (734, '- Isopropylbenzene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (735, '- Bromobenzene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (736, '- n-Propylbenzene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (737, '- 2- Chlorotoluene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (738, '- 4-Chlorotoluene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (739, '- 1,3,5- Trimethylbenzene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (740, '- Tert- Butylbenzene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (741, '- 1,2,4-Trimethylbenzene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (742, '- Sec-Butylbenzene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (743, '- 1,3- Dichlorobenzene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (744, '- 1,4- Dichlorobenzene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (745, '- p- Isopropyltoluene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (746, '- 1,2-Dichlorobenzene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (747, '- nButhylbenzene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (748, '- 1,2,4- Trichlorobenzene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (749, '- Naphthalene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (750, '- 1,2,3-Trichlorobenzene ²', '0,3333', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (751, 'Puntos de muestreo transversales para medición de velocidad en fuentes estacionarias\nVelocidad y flujo volumétrico', '1', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (752, 'Velocidad y flujo volumétrico', '', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (753, 'Velocidad y flujo volumétrico\n(Continúa)', '0,1', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (754, 'Humedad', '', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (755, 'Opacidad', '', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (756, '- Oxygen (O2).²', '0,01', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (757, '- Carbon Dioxide (CO2). ²', '0,01', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (758, '- Carbon Monoxide (CO) ²', '', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (759, '- Oxygen (O2) ²', '0,01', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (760, '- Carbon Dioxide (CO2) ²', '', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (761, 'Carbon Monoxide (CO)', '', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (762, '- Nitrogen Oxides (NOx) ²', '2,05', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (763, '- Nitric Oxide (NO) ²', '1,34', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (764, '- Nitrogen Dioxide (NO2) ²', '0,21', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (765, 'Sulfuro de hidrógeno: H2S', '0,15', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (766, 'Hidrocarburos Totales:HT', '', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (767, 'Dióxido de carbono:CO2', '0,01', 113, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (768, '- Monóxido de Carbono (CO).', '1,16', 87, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (769, '- Monóxido de Nitrógeno (NO).', '1,25', 87, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (770, '- Dioxido de Nitrogeno (NO2).', '0,19', 87, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (771, '- Oxígeno (O2).', '-', 82, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (772, '- Oxido de Nitrógeno (NOx).', '1,91', 87, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (773, 'Dióxido de Azufre (SO2)', '2,86', 87, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (774, 'Material Particulado  - (ISOCINÉTICO)  \n(filtro 90 mm)', '5,5', 87, 19, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (775, 'Carbon Monoxide (CO)', '1,25', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (776, 'Material Particulado\n(Cálculo)', '-', 87, 24, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (777, '- Antimony (Sb) ²', '0,0145', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (778, '- Arsenic (As) ²', '0,0177', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (779, '- Barium (Ba) ²', '0,0021', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (780, '- Beryllium (Be) ²', '0,0011', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (781, '- Cadmium (Cd) ²', '0,0031', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (782, '- Chromium (Cr) ²', '0,0073', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (783, '- Cobalt (Co) ²', '0,0093', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (784, '- Copper (Cu) ²', '0,0063', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (785, '- Lead (Pb) ²', '0,0207', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (786, '- Manganese (Mn) ²', '0,0025', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (787, '- Mercury (Hg) ²', '0,0363', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (788, '- Nickel (Ni) ²', '0,0083', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (789, '- Phosphorus (P) ²', '0,0611', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (790, '- Selenium (Se) ²', '0,0963', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (791, '- Silver (Ag) ²', '0,0963', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (792, '- Thallium (Ti) ²', '0,04', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (793, '- Zinc (Zn) ²', '0,08', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (794, '- Vanadium (V) ²', '0,0043', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (795, '- Iron (Fe) ²', '0,0223', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (796, '- Tin (Sn) ²', '0,0274', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (797, '- Titanium (Ti) ²', '0,002', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (798, 'TRS (disulfuro de dimetilo, sulfuro de dimetilo, sulfuro de hidrógeno, metilmercaptano, sulfuro de carbonilo', '', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (799, 'Dióxido de azufre en emisiones gaseosas\nSO2', '', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (800, 'Ácido sulfúrico y dióxido de azufre en emisiones gaseosas', '', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (801, 'NOx en emisiones gaseosas\n(NO y NO2)', '', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (802, 'NOx en emisiones gaseosas\n(NO; NO2)', '', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (803, '- Benzene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (804, '- Trichloroethene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (805, '- Toluene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (806, '- Tetrachloroethene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (807, '- Chlorobenzene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (808, '- Ethylbenzene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (809, '- m-Xylene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (810, '- P-Xylene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (811, '- o-Xylene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (812, '- m,p-Xylene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (813, '- Styrene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (814, '- Isopropylbenzene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (815, '- Bromobenzene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (816, '- n-Propylbenzene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (817, '- 2- Chlorotoluene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (818, '- 4-Chlorotoluene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (819, '- 1,3,5- Trimethylbenzene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (820, '- Tert- Butylbenzene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (821, '- 1,2,4-Trimethylbenzene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (822, '- Sec-Butylbenzene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (823, '- 1,3- Dichlorobenzene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (824, '- 1,4- Dichlorobenzene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (825, '- p- Isopropyltoluene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (826, '- 1,2-Dichlorobenzene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (827, '- nButhylbenzene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (828, '- 1,2,4- Trichlorobenzene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (829, '- Naphthalene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (830, '- 1,2,3-Trichlorobenzene ²', '0,3333', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (831, 'Puntos de muestreo transversales para medición de velocidad en fuentes estacionarias\nVelocidad y flujo volumétrico', '', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (832, 'Velocidad y flujo volumétrico', '', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (833, 'Velocidad y flujo volumétrico\n(Continúa)', '', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (834, 'Humedad', '', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (835, 'Opacidad', '', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (836, '- Oxygen (O2).²', '0,01', 87, 20, '2026-04-11 12:57:34', '2026-04-11 17:57:34', NULL);
INSERT INTO `essays` VALUES (837, '- Carbon Dioxide (CO2). ²', '0,01', 87, 20, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (838, '- Carbon Monoxide (CO) ²', '', 87, 20, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (839, '- Oxygen (O2) ²', '0,01', 87, 20, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (840, '- Carbon Dioxide (CO2) ²', '', 87, 20, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (841, '- Nitrogen Oxides (NOx) ²', '1,91', 87, 20, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (842, '- Nitric Oxide (NO) ²', '1,25', 87, 20, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (843, '- Nitrogen Dioxide (NO2) ²', '0,19', 87, 20, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (844, 'Sulfuro de hidrógeno: H2S', '0,14', 87, 20, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (845, 'Hidrocarburos Totales:HT', '', 87, 20, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (846, 'Dióxido de carbono:CO2', '0,01', 87, 20, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (847, 'Temperatura (campo)', 'N.A.', 81, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (848, 'pH (campo)', 'N.A.', 90, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (849, 'Conductividad (campo)', '0.05', 88, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (850, 'Turbidez (campo)', '0.2', 91, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (851, 'Oxígeno Disuelto (campo)', '0.03', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (852, 'Sólidos Totales Disueltos', '3', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (853, 'Sólidos Totales Suspendidos', '3', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (854, 'Color', '-', 114, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (855, 'cloro residual', '-', 89, 27, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (856, 'Coliformes fecales o termotolerantes', '1.8', 99, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (857, 'Escherichia Coli', '1.1', 99, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (858, 'Coliformes Totales', '1.8', 99, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (859, 'Heterotrophic Bacteria', '1', 102, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (860, 'Huevos de hemintos\nHELMINTH EGGS\nNematodes\nAscaris sp.\nUncinarias (Ancylostoma, Strongylus, \nNecator)\nEnterobius vermicularis\nTrichuris sp.\nToxocara sp.\nCapillaria sp.\nTrichostrongylus sp.\nCestodes\nDyphylidium sp.\nTaenia sp.\nHymenolepis diminuta\nHymenolepis nana\nHymenolepis sp.\nTrematodes\nFasciola hepatica\nParagonimus sp.\nSchistosoma sp.\nAcantocephalus\nMacracanthorhynchus sp', '1', 100, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (861, 'Parásitos y Protozoarios (Huevos, Quistes y Ooquistes) PARASITES AND PROTOZOARIES \nPARASITES AND PROTOZOARIES \nNematodes\nAscaris sp.\nAncilostomídeos (Ancylostoma, \nNecator)\nEnterobius vermicularis\nTrichuris sp.\nToxocara sp.\nCapillaria sp.\nTrichostrongylus sp.\nCestodes\nDyphylidium sp.\nTaenia sp.\nHymenolepis diminuta\nHymenolepis nana\nHymenolepis sp.\nTrematodes\nFasciola hepatica\nParagonimus sp.\nSchistosoma sp.\nProtozoa - Amoebas and \nFlagellates\nEndolimax nana\nEntamoeba histolytica\nEntamoeba coli\nGiardia sp.\nIodamoeba sp.\nChilomastix sp.\nBlastocystis hominis\nBalantidium coli\nAcanthamoeba sp.\nCoccidia\nIsospora sp.', '1', 115, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (862, 'Virus (Colifagos) (36.5 ± 2°C)', '1', 101, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (863, 'ALGAS; LARVAS U ORGANISMOS DE VIDA LIBRE (VIVOS \nORGANISMOS)\nPhytoplankton (Algae) + Zooplankton (Protozoa, rotifers, copepods and\nnematodes)', '1', 100, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (864, 'Cloruro', '3', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (865, 'Sulfato', '3', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (866, 'Dureza Total', '1.1', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (867, 'Amoniaco (NH3) (electrodo)', '0.01', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (868, 'Metales Totales (ICP-AES)                                     Aluminio, Cobre, Hierro, Manganeso, Sodio,Zinc', '', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (869, 'Aceites y Grasas', '0.4', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (870, 'Bicarbonatos', '1', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (871, 'Cianuro Wad', '0.025', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (872, 'Color', '', 98, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (873, 'Demanda Química de Oxigeno - (DBO)', '', 89, 27, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (874, 'Demanda Bioquímica de Oxígeno - DQO', '5', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (875, 'Detergentes SAAM', '', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (876, 'Fenoles', '0.003', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (877, 'Fluoruro', '0.05', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (878, 'Nitratos (NO3--N) +\nNitritos (NO2--N)', '', 89, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (879, 'Nitrito', '0.01', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (880, 'pH (campo)', '0.01', 90, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (881, 'Temperatura (campo)', '0.1', 81, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (882, 'Metales Totales (ICP-OES)                                        Plata, Aluminio, Arsénico, Bario, Berilio, Boro Calcio, Cadmio, Cobalto, Cromo, Cerio, Cobre, Hierro, Potasio, Litio, Magnesio,  Manganeso, Molibdeno, Mercurio, Sodio, Níquel,  Fosforo, Plomo, Antimonio, Selenio, Sílice (SiO2), Estroncio, Estaño, Titanio, Talio,  Vanadio, Zinc', '', 89, 25, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (883, 'Bifenilos Policlorados (PCB', '0,000001', 89, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (884, 'Pesticidas Organofosforados\n Parathion', '0.0001', 89, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (885, 'Organochlorine Pesticides\nAldrín + Dieldrín\nClordano\nDicloro Difenil Tricloroetano\n(DDT)\nEndrin\nEndosulfan I\nHeptacloro + Heptacloro\nEpóxido\nLindano', '2.0E-7', 89, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (886, 'Carbamato\nAldicarb', '0,0001', 89, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (887, 'Coliformes fecales o termotolerantes', '1,8', 99, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (888, 'Escherichia Coli', '1,8', 116, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (889, 'Helminth eggs', '-', 117, 26, '2026-04-11 12:57:35', '2026-04-11 17:57:35', NULL);
INSERT INTO `essays` VALUES (890, '', NULL, NULL, NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);

-- ----------------------------
-- Table structure for items_matriz
-- ----------------------------
DROP TABLE IF EXISTS `items_matriz`;
CREATE TABLE `items_matriz`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `matriz_id` bigint UNSIGNED NULL DEFAULT NULL,
  `essays_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `items_matriz_matriz_id_foreign`(`matriz_id` ASC) USING BTREE,
  INDEX `items_matriz_essays_id_foreign`(`essays_id` ASC) USING BTREE,
  CONSTRAINT `items_matriz_essays_id_foreign` FOREIGN KEY (`essays_id`) REFERENCES `essays` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `items_matriz_matriz_id_foreign` FOREIGN KEY (`matriz_id`) REFERENCES `matriz` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 442 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of items_matriz
-- ----------------------------
INSERT INTO `items_matriz` VALUES (1, 49, 409, '2026-04-11 14:08:51', '2026-04-11 19:08:51', NULL);
INSERT INTO `items_matriz` VALUES (2, 50, 410, '2026-04-11 14:10:27', '2026-04-11 19:10:27', NULL);
INSERT INTO `items_matriz` VALUES (3, 51, 411, '2026-04-11 14:11:10', '2026-04-11 19:11:10', NULL);
INSERT INTO `items_matriz` VALUES (4, 52, 412, '2026-04-11 14:11:57', '2026-04-11 19:11:57', NULL);
INSERT INTO `items_matriz` VALUES (5, 53, 413, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (6, 53, 414, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (7, 53, 415, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (8, 53, 416, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (9, 53, 417, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (10, 53, 418, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (11, 53, 419, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (12, 53, 420, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (13, 53, 421, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (14, 53, 422, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (15, 53, 423, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (16, 53, 424, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (17, 53, 425, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (18, 53, 426, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (19, 53, 427, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (20, 53, 428, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (21, 53, 429, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (22, 53, 430, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (23, 53, 431, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (24, 53, 432, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (25, 53, 433, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (26, 53, 434, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (27, 53, 435, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (28, 53, 436, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (29, 53, 437, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (30, 53, 438, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (31, 53, 439, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (32, 53, 440, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (33, 53, 441, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `items_matriz` VALUES (34, 54, 413, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (35, 54, 414, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (36, 54, 415, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (37, 54, 416, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (38, 54, 417, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (39, 54, 418, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (40, 54, 419, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (41, 54, 420, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (42, 54, 421, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (43, 54, 422, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (44, 54, 423, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (45, 54, 454, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (46, 54, 425, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (47, 54, 426, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (48, 54, 427, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (49, 54, 428, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (50, 54, 429, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (51, 54, 430, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (52, 54, 431, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (53, 54, 432, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (54, 54, 433, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (55, 54, 434, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (56, 54, 435, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (57, 54, 436, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (58, 54, 437, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (59, 54, 438, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (60, 54, 439, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (61, 54, 440, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (62, 54, 441, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `items_matriz` VALUES (63, 55, 413, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (64, 55, 414, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (65, 55, 415, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (66, 55, 416, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (67, 55, 417, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (68, 55, 418, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (69, 55, 419, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (70, 55, 420, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (71, 55, 421, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (72, 55, 422, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (73, 55, 423, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (74, 55, 424, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (75, 55, 454, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (76, 55, 425, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (77, 55, 426, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (78, 55, 427, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (79, 55, 428, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (80, 55, 429, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (81, 55, 492, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (82, 55, 430, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (83, 55, 431, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (84, 55, 432, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (85, 55, 434, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (86, 55, 433, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (87, 55, 441, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (88, 55, 435, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (89, 55, 436, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (90, 55, 437, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (91, 55, 438, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (92, 55, 439, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (93, 55, 440, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `items_matriz` VALUES (94, 56, 505, '2026-04-11 14:44:47', '2026-04-11 19:44:47', NULL);
INSERT INTO `items_matriz` VALUES (95, 56, 506, '2026-04-11 14:44:47', '2026-04-11 19:44:47', NULL);
INSERT INTO `items_matriz` VALUES (96, 56, 507, '2026-04-11 14:44:47', '2026-04-11 19:44:47', NULL);
INSERT INTO `items_matriz` VALUES (97, 56, 508, '2026-04-11 14:44:47', '2026-04-11 19:44:47', NULL);
INSERT INTO `items_matriz` VALUES (98, 56, 509, '2026-04-11 14:44:47', '2026-04-11 19:44:47', NULL);
INSERT INTO `items_matriz` VALUES (99, 56, 510, '2026-04-11 14:44:47', '2026-04-11 19:44:47', NULL);
INSERT INTO `items_matriz` VALUES (100, 57, 626, '2026-04-11 15:30:56', '2026-04-11 20:30:56', NULL);
INSERT INTO `items_matriz` VALUES (101, 57, 627, '2026-04-11 15:30:56', '2026-04-11 20:30:56', NULL);
INSERT INTO `items_matriz` VALUES (102, 57, 628, '2026-04-11 15:30:56', '2026-04-11 20:30:56', NULL);
INSERT INTO `items_matriz` VALUES (103, 57, 629, '2026-04-11 15:30:56', '2026-04-11 20:30:56', NULL);
INSERT INTO `items_matriz` VALUES (104, 57, 631, '2026-04-11 15:30:56', '2026-04-11 20:30:56', NULL);
INSERT INTO `items_matriz` VALUES (105, 57, 630, '2026-04-11 15:30:56', '2026-04-11 20:30:56', NULL);
INSERT INTO `items_matriz` VALUES (106, 58, 632, '2026-04-11 16:00:35', '2026-04-11 21:00:35', NULL);
INSERT INTO `items_matriz` VALUES (107, 58, 633, '2026-04-11 16:00:35', '2026-04-11 21:00:35', NULL);
INSERT INTO `items_matriz` VALUES (108, 59, 472, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (109, 60, 472, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (110, 61, 511, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (111, 62, 512, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (112, 63, 513, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (113, 64, 514, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (114, 65, 515, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (115, 66, 516, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (116, 67, 517, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (117, 68, 518, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (118, 69, 519, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (119, 70, 520, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (120, 71, 521, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (121, 72, 522, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (122, 73, 523, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (123, 74, 524, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (124, 75, 525, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (125, 76, 526, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (126, 77, 527, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (127, 78, 528, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (128, 79, 529, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (129, 80, 530, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (130, 81, 531, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (131, 82, 532, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (132, 83, 533, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (133, 84, 534, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (134, 85, 535, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (135, 86, 536, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (136, 87, 537, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (137, 88, 538, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (138, 89, 539, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (139, 90, 540, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (140, 91, 541, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (141, 92, 542, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (142, 93, 543, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (143, 94, 544, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (144, 95, 545, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (145, 96, 546, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (146, 97, 547, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (147, 98, 548, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (148, 99, 549, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (149, 100, 550, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (150, 101, 551, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (151, 102, 552, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (152, 103, 553, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (153, 104, 554, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (154, 105, 555, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (155, 106, 556, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (156, 107, 557, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (157, 108, 558, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (158, 109, 564, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (159, 110, 565, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (160, 111, 566, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (161, 112, 567, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (162, 113, 566, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `items_matriz` VALUES (163, 114, 569, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (164, 115, 570, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (165, 116, 571, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (166, 117, 572, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (167, 118, 573, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (168, 119, 574, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (169, 120, 575, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (170, 121, 576, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (171, 122, 577, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (172, 123, 572, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (173, 124, 578, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (174, 125, 579, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (175, 126, 580, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (176, 127, 581, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (177, 128, 582, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (178, 129, 583, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (179, 130, 584, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (180, 131, 585, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (181, 132, 586, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (182, 133, 587, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (183, 134, 564, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (184, 135, 588, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (185, 136, 579, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (186, 137, 589, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (187, 138, 590, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (188, 139, 591, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (189, 140, 592, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (190, 141, 593, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (191, 142, 594, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (192, 143, 595, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (193, 144, 596, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (194, 145, 597, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (195, 146, 598, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (196, 147, 599, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (197, 148, 600, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (198, 149, 601, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (199, 150, 602, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (200, 151, 603, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (201, 152, 604, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (202, 153, 605, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (203, 154, 606, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (204, 155, 607, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (205, 156, 608, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (206, 157, 609, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (207, 158, 610, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (208, 159, 611, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (209, 160, 612, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (210, 161, 613, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (211, 162, 614, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (212, 163, 615, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (213, 164, 616, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (214, 165, 617, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (215, 166, 618, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (216, 167, 556, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (217, 168, 553, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (218, 169, 621, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (219, 170, 622, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (220, 171, 623, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (221, 172, 624, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (222, 173, 646, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (223, 174, 623, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (224, 175, 624, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (225, 176, 632, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (226, 177, 556, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (227, 178, 553, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (228, 179, 673, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (229, 180, 674, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (230, 181, 675, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (231, 182, 674, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (232, 183, 675, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (233, 184, 676, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (234, 185, 677, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (235, 186, 678, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (236, 187, 678, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (237, 188, 679, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (238, 189, 680, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (239, 190, 681, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (240, 191, 682, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (241, 192, 683, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (242, 193, 684, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (243, 194, 685, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (244, 195, 686, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (245, 196, 687, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (246, 197, 688, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (247, 198, 689, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (248, 199, 694, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (249, 200, 695, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (250, 201, 696, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (251, 202, 718, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (252, 203, 719, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (253, 204, 720, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (254, 205, 721, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (255, 206, 722, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (256, 207, 751, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (257, 208, 752, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (258, 209, 753, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (259, 210, 754, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (260, 211, 755, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (261, 212, 756, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (262, 213, 757, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (263, 214, 695, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (264, 215, 694, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (265, 216, 774, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (266, 217, 695, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (267, 218, 696, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (268, 219, 718, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (269, 220, 719, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (270, 221, 720, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (271, 222, 721, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (272, 223, 722, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (273, 224, 751, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (274, 225, 752, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (275, 226, 753, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (276, 227, 754, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (277, 228, 755, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (278, 229, 756, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (279, 230, 757, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (280, 231, 758, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (281, 232, 695, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (282, 233, 762, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (283, 234, 762, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (284, 235, 765, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (285, 236, 765, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (286, 237, 765, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (287, 238, 532, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (288, 239, 531, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (289, 240, 529, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (290, 241, 533, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (291, 242, 530, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (292, 243, 575, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (293, 244, 576, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (294, 245, 587, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (295, 246, 855, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (296, 247, 601, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (297, 248, 603, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (298, 249, 602, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (299, 250, 608, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (300, 251, 607, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (301, 252, 604, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (302, 253, 605, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (303, 254, 606, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (304, 255, 544, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (305, 256, 545, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (306, 257, 587, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (307, 258, 531, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (308, 259, 533, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (309, 260, 529, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (310, 261, 575, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (311, 262, 555, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (312, 263, 563, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (313, 264, 558, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (314, 265, 867, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (315, 266, 868, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (316, 267, 546, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (317, 268, 549, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (318, 269, 554, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (319, 270, 555, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (320, 271, 587, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (321, 272, 529, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (322, 273, 873, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (323, 274, 874, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (324, 275, 875, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (325, 276, 569, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (326, 277, 559, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (327, 278, 878, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (328, 279, 562, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `items_matriz` VALUES (329, 280, 530, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `items_matriz` VALUES (330, 281, 531, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `items_matriz` VALUES (331, 282, 563, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `items_matriz` VALUES (332, 283, 532, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `items_matriz` VALUES (333, 284, 581, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `items_matriz` VALUES (334, 285, 883, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `items_matriz` VALUES (335, 286, 884, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `items_matriz` VALUES (336, 287, 595, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `items_matriz` VALUES (337, 288, 598, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `items_matriz` VALUES (338, 289, 601, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `items_matriz` VALUES (339, 290, 603, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `items_matriz` VALUES (340, 291, 609, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `items_matriz` VALUES (341, 292, 555, '2026-04-11 16:48:03', '2026-04-11 21:48:03', NULL);
INSERT INTO `items_matriz` VALUES (342, 293, 559, '2026-04-11 16:48:03', '2026-04-11 21:48:03', NULL);
INSERT INTO `items_matriz` VALUES (343, 294, 560, '2026-04-11 16:48:03', '2026-04-11 21:48:03', NULL);
INSERT INTO `items_matriz` VALUES (344, 295, 561, '2026-04-11 16:48:03', '2026-04-11 21:48:03', NULL);
INSERT INTO `items_matriz` VALUES (345, 296, 562, '2026-04-11 16:48:03', '2026-04-11 21:48:03', NULL);
INSERT INTO `items_matriz` VALUES (346, 297, 563, '2026-04-11 16:48:03', '2026-04-11 21:48:03', NULL);
INSERT INTO `items_matriz` VALUES (347, 298, 669, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (348, 298, 670, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (349, 298, 671, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (350, 298, 672, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (351, 299, 689, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (352, 299, 690, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (353, 299, 691, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (354, 299, 692, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (355, 299, 693, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (356, 300, 714, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (357, 300, 715, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (358, 300, 716, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (359, 300, 717, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (360, 301, 723, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (361, 301, 724, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (362, 301, 725, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (363, 301, 726, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (364, 301, 727, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (365, 301, 728, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (366, 301, 729, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (367, 301, 730, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (368, 301, 731, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (369, 301, 732, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (370, 301, 733, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (371, 301, 734, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (372, 301, 735, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (373, 301, 736, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (374, 301, 737, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (375, 301, 738, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (376, 301, 739, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (377, 301, 740, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (378, 301, 741, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (379, 301, 742, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (380, 301, 743, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (381, 301, 744, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (382, 301, 745, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (383, 301, 746, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (384, 301, 747, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (385, 301, 748, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (386, 301, 749, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (387, 301, 750, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (388, 302, 758, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (389, 302, 759, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (390, 302, 760, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (391, 303, 762, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (392, 303, 763, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (393, 303, 764, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (394, 304, 762, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (395, 304, 763, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (396, 304, 764, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (397, 305, 765, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (398, 305, 766, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (399, 305, 767, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (400, 306, 765, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (401, 306, 766, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (402, 306, 767, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (403, 307, 689, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (404, 307, 690, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (405, 307, 691, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (406, 307, 692, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (407, 307, 693, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (408, 308, 723, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (409, 308, 724, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (410, 308, 725, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (411, 308, 726, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (412, 308, 727, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (413, 308, 728, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (414, 308, 729, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (415, 308, 730, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (416, 308, 731, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (417, 308, 732, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (418, 308, 733, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (419, 308, 734, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (420, 308, 735, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (421, 308, 736, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (422, 308, 737, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (423, 308, 738, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (424, 308, 739, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (425, 308, 740, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (426, 308, 741, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (427, 308, 742, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (428, 308, 743, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (429, 308, 744, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (430, 308, 745, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (431, 308, 746, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (432, 308, 747, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (433, 308, 748, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (434, 308, 749, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (435, 308, 750, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (436, 309, 762, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (437, 309, 763, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (438, 309, 764, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (439, 310, 762, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (440, 310, 763, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `items_matriz` VALUES (441, 310, 764, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);

-- ----------------------------
-- Table structure for items_order_service
-- ----------------------------
DROP TABLE IF EXISTS `items_order_service`;
CREATE TABLE `items_order_service`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_service_id` bigint UNSIGNED NULL DEFAULT NULL,
  `filable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `filable_id` bigint UNSIGNED NULL DEFAULT NULL,
  `item` json NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` int NULL DEFAULT NULL,
  `price_unit` decimal(10, 2) NULL DEFAULT NULL,
  `total` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `items_order_service_order_service_id_foreign`(`order_service_id` ASC) USING BTREE,
  CONSTRAINT `items_order_service_order_service_id_foreign` FOREIGN KEY (`order_service_id`) REFERENCES `order_service` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of items_order_service
-- ----------------------------
INSERT INTO `items_order_service` VALUES (9, 4, 'App\\Models\\tenant\\Matriz', 310, '{\"bg\": \"bg-teal-50\", \"id\": 310, \"price\": 350, \"essays\": [{\"id\": 762, \"lcm\": \"2,05\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Nitrogen Oxides (NOx) ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 763, \"lcm\": \"1,34\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Nitric Oxide (NO) ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 764, \"lcm\": \"0,21\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Nitrogen Dioxide (NO2) ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}], \"frequency\": \"monthly\", \"created_at\": \"2026-04-11 13:06:00\", \"unit_price\": \"350.00\", \"updated_at\": \"2026-04-11T23:06:00.000000Z\", \"description\": \"tipo de combustible: solo gas natural\\n            equipo:testo\\n            selección de sitio de muestreo:motores reciprocantes,calderos y hornos de proceso\", \"methodologie\": {\"id\": 567, \"info\": null, \"created_at\": \"2026-04-11 13:06:00\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T23:06:00.000000Z\", \"description\": \"EPA CTM-030\\n                    Determination of Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas-Fired Engines, Boilers and Process Heaters Using Portable Analyzers. 1997\"}, \"number_samples\": 1, \"frequency_label\": \"Mensual (cada mes)\", \"methodologie_id\": 567}', 'matriz', 1, 350.00, 350.00, '2026-04-12 23:48:47', '2026-04-13 04:48:47', NULL);
INSERT INTO `items_order_service` VALUES (10, 4, 'App\\Models\\tenant\\Matriz', 309, '{\"bg\": \"bg-teal-50\", \"id\": 309, \"price\": 350, \"essays\": [{\"id\": 762, \"lcm\": \"2,05\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Nitrogen Oxides (NOx) ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 763, \"lcm\": \"1,34\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Nitric Oxide (NO) ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 764, \"lcm\": \"0,21\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Nitrogen Dioxide (NO2) ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}], \"frequency\": \"monthly\", \"created_at\": \"2026-04-11 13:06:00\", \"unit_price\": \"350.00\", \"updated_at\": \"2026-04-11T23:06:00.000000Z\", \"description\": \"todo tipo de combustible equipo:testo\", \"methodologie\": {\"id\": 570, \"info\": null, \"created_at\": \"2026-04-11 13:06:00\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T23:06:00.000000Z\", \"description\": \"EPA CTM-022\\n                    Determination of Nitric Oxide, Nitrogen Dioxide\\n                    and NOx Emissions from Stationary Combustion Sources by electrochemical analyzer. 1995\"}, \"number_samples\": 1, \"frequency_label\": \"Mensual (cada mes)\", \"methodologie_id\": 570}', 'matriz', 1, 350.00, 350.00, '2026-04-12 23:48:47', '2026-04-13 04:48:47', NULL);
INSERT INTO `items_order_service` VALUES (11, 4, 'App\\Models\\tenant\\Matriz', 308, '{\"bg\": \"bg-cyan-50\", \"id\": 308, \"price\": 600, \"essays\": [{\"id\": 723, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Benzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 724, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Trichloroethene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 725, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Toluene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 726, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Tetrachloroethene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 727, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Chlorobenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 728, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Ethylbenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 729, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- m-Xylene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 730, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- P-Xylene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 731, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- o-Xylene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 732, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- m,p-Xylene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 733, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Styrene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 734, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Isopropylbenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 735, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Bromobenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 736, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- n-Propylbenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 737, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 2- Chlorotoluene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 738, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 4-Chlorotoluene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 739, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 1,3,5- Trimethylbenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 740, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Tert- Butylbenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 741, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 1,2,4-Trimethylbenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 742, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Sec-Butylbenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 743, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 1,3- Dichlorobenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 744, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 1,4- Dichlorobenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 745, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- p- Isopropyltoluene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 746, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 1,2-Dichlorobenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 747, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- nButhylbenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 748, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 1,2,4- Trichlorobenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 749, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Naphthalene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 750, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 1,2,3-Trichlorobenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}], \"frequency\": \"bimonthly\", \"created_at\": \"2026-04-11 13:06:00\", \"unit_price\": \"600.00\", \"updated_at\": \"2026-04-11T23:06:00.000000Z\", \"description\": \"VOCS Equipo:Isocinetico pequeño\", \"methodologie\": {\"id\": 564, \"info\": null, \"created_at\": \"2026-04-11 13:06:00\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T23:06:00.000000Z\", \"description\": \"EPA Method 18 (7-1-23)\\n                    Measurement of Gaseous Organic Compound Emissions by Gas Chromatography\\n                    NTP 900.018:2021 \\n                    ATMOSPHERIC EMISSIONS MONITORING.Measurement of Gaseous Organic Compound Emissions by Gas Chromatography\"}, \"number_samples\": 1, \"frequency_label\": \"Bimestral (cada 2 meses)\", \"methodologie_id\": 564}', 'matriz', 1, 600.00, 600.00, '2026-04-12 23:48:47', '2026-04-13 04:48:47', NULL);
INSERT INTO `items_order_service` VALUES (12, 4, 'App\\Models\\tenant\\Matriz', 297, '{\"bg\": \"bg-cyan-50\", \"id\": 297, \"price\": 20, \"essays\": [{\"id\": 563, \"lcm\": \"3\", \"condition\": {\"id\": 19, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE INACAL\"}, \"description\": \"Sulfato\", \"condition_id\": 19, \"units_measurement\": {\"id\": 89, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/L\"}, \"units_measurement_id\": 89}], \"frequency\": \"bimonthly\", \"created_at\": \"2026-04-11 11:48:03\", \"unit_price\": \"20.00\", \"updated_at\": \"2026-04-11T21:48:03.000000Z\", \"description\": \"Agua \\nAniones\", \"methodologie\": {\"id\": 429, \"info\": null, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"Sulfate. Turbidimetric Method                                                                                 SMEWW-APHA-AWWA-WEF Part 4500-SO4= E, 24th Ed. (2023)\"}, \"number_samples\": 1, \"frequency_label\": \"Bimestral (cada 2 meses)\", \"methodologie_id\": 429}', 'matriz', 1, 20.00, 20.00, '2026-04-12 23:48:47', '2026-04-13 04:48:47', NULL);
INSERT INTO `items_order_service` VALUES (13, 4, 'App\\Models\\tenant\\Matriz', 299, '{\"bg\": \"bg-cyan-50\", \"id\": 299, \"price\": 350, \"essays\": [{\"id\": 689, \"lcm\": \"1,25\", \"condition\": {\"id\": 19, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE INACAL\"}, \"description\": \"- Monóxido de Carbono (CO).\", \"condition_id\": 19, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 690, \"lcm\": \"1,34\", \"condition\": {\"id\": 19, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE INACAL\"}, \"description\": \"- Monóxido de Nitrógeno (NO).\", \"condition_id\": 19, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 691, \"lcm\": \"0,21\", \"condition\": {\"id\": 19, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE INACAL\"}, \"description\": \"- Dioxido de Nitrogeno (NO2).\", \"condition_id\": 19, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 692, \"lcm\": \"0-01\", \"condition\": {\"id\": 19, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE INACAL\"}, \"description\": \"- Oxígeno (O2).\", \"condition_id\": 19, \"units_measurement\": {\"id\": 82, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"%\"}, \"units_measurement_id\": 82}, {\"id\": 693, \"lcm\": \"2,05\", \"condition\": {\"id\": 19, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE INACAL\"}, \"description\": \"- Oxido de Nitrógeno (NOx).\", \"condition_id\": 19, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}], \"frequency\": \"bimonthly\", \"created_at\": \"2026-04-11 13:06:00\", \"unit_price\": \"350.00\", \"updated_at\": \"2026-04-11T23:06:00.000000Z\", \"description\": \"EPA Method 8270E Rev 6 Jun 2018. // EPA Method 3550C Rev 3 February 2007\\n                Semi-volatile Organic Compounds by Gas Chromatography/Mass Spectrometry // Ultrasonic Extraction\", \"methodologie\": {\"id\": 509, \"info\": null, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"Determination of Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas-Fired Engines, Boilers and Process Heaters Using Portable Analyzers  CTM 034 (1997)\"}, \"number_samples\": 1, \"frequency_label\": \"Bimestral (cada 2 meses)\", \"methodologie_id\": 509}', 'matriz', 1, 350.00, 350.00, '2026-04-12 23:48:47', '2026-04-13 04:48:47', NULL);
INSERT INTO `items_order_service` VALUES (14, 4, 'App\\Models\\tenant\\Matriz', 285, '{\"bg\": \"bg-cyan-50\", \"id\": 285, \"price\": 250, \"essays\": [{\"id\": 883, \"lcm\": \"0,000001\", \"condition\": {\"id\": 26, \"info\": null, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"IAS(*)\"}, \"description\": \"Bifenilos Policlorados (PCB\", \"condition_id\": 26, \"units_measurement\": {\"id\": 89, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/L\"}, \"units_measurement_id\": 89}], \"frequency\": \"bimonthly\", \"created_at\": \"2026-04-11 11:19:49\", \"unit_price\": \"250.00\", \"updated_at\": \"2026-04-11T21:19:49.000000Z\", \"description\": \"Agua\", \"methodologie\": {\"id\": 553, \"info\": null, \"created_at\": \"2026-04-11 07:57:33\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:33.000000Z\", \"description\": \"Polychlorinated Biphenyls (PCBs) by Gas Chromatography Separatory funnel liquid-liquid extraction.EPA Method 8082A Revision 1 February 2007 // EPA Method 3510C Revision 3 December 1996(Cromatografía de Gases con detector de captura de electrones (ECD))\"}, \"number_samples\": 1, \"frequency_label\": \"Bimestral (cada 2 meses)\", \"methodologie_id\": 553}', 'matriz', 1, 250.00, 250.00, '2026-04-12 23:48:47', '2026-04-13 04:48:47', NULL);
INSERT INTO `items_order_service` VALUES (15, 4, 'App\\Models\\tenant\\Matriz', 289, '{\"id\": 289, \"price\": 50, \"essays\": [{\"id\": 601, \"lcm\": \"1.8\", \"condition\": {\"id\": 23, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS(*)\"}, \"description\": \"Coliformes fecales o termotolerantes\", \"condition_id\": 23, \"units_measurement\": {\"id\": 99, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"NMP/100mL\"}, \"units_measurement_id\": 99}], \"created_at\": \"2026-04-11 11:19:49\", \"unit_price\": \"50.00\", \"updated_at\": \"2026-04-11T21:19:49.000000Z\", \"description\": \"Agua\", \"methodologie\": {\"id\": 556, \"info\": null, \"created_at\": \"2026-04-11 07:57:33\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:33.000000Z\", \"description\": \"Enumeration of Fecal Coliforms by MPN method Fecal Coliform ProcedureSM 9221 E. / 9221C. Standard Methods 23rd Edition 2017\"}, \"number_samples\": 1, \"methodologie_id\": 556}', 'matriz', 1, 50.00, 50.00, '2026-04-12 23:48:47', '2026-04-13 04:48:47', NULL);
INSERT INTO `items_order_service` VALUES (16, 4, 'App\\Models\\tenant\\Matriz', 293, '{\"id\": 293, \"price\": 40, \"essays\": [{\"id\": 559, \"lcm\": \"0.05\", \"condition\": {\"id\": 19, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE INACAL\"}, \"description\": \"Fluoruro\", \"condition_id\": 19, \"units_measurement\": {\"id\": 89, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/L\"}, \"units_measurement_id\": 89}], \"created_at\": \"2026-04-11 11:48:03\", \"unit_price\": \"40.00\", \"updated_at\": \"2026-04-11T21:48:03.000000Z\", \"description\": \"Agua \\nAniones\", \"methodologie\": {\"id\": 425, \"info\": null, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"Análisis de Aguas - Determinación de fluroruros en aguas naturales, residuales y residuales tratadas                                                                        NMX-AA-077-SCFI-2001 (2001)\"}, \"number_samples\": 1, \"methodologie_id\": 425}', 'matriz', 1, 40.00, 40.00, '2026-04-12 23:48:47', '2026-04-13 04:48:47', NULL);

-- ----------------------------
-- Table structure for items_quotes
-- ----------------------------
DROP TABLE IF EXISTS `items_quotes`;
CREATE TABLE `items_quotes`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `quote_id` bigint UNSIGNED NULL DEFAULT NULL,
  `filable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `filable_id` bigint UNSIGNED NULL DEFAULT NULL,
  `item` json NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `amount` int NULL DEFAULT NULL,
  `price_unit` decimal(10, 2) NULL DEFAULT NULL,
  `total` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `items_quotes_quote_id_foreign`(`quote_id` ASC) USING BTREE,
  CONSTRAINT `items_quotes_quote_id_foreign` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of items_quotes
-- ----------------------------
INSERT INTO `items_quotes` VALUES (1, 1, 'App\\Models\\tenant\\Matriz', 310, '{\"bg\": \"bg-teal-50\", \"id\": 310, \"price\": 350, \"essays\": [{\"id\": 762, \"lcm\": \"2,05\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Nitrogen Oxides (NOx) ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 763, \"lcm\": \"1,34\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Nitric Oxide (NO) ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 764, \"lcm\": \"0,21\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Nitrogen Dioxide (NO2) ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}], \"frequency\": \"monthly\", \"created_at\": \"2026-04-11 13:06:00\", \"unit_price\": \"350.00\", \"updated_at\": \"2026-04-11T23:06:00.000000Z\", \"description\": \"tipo de combustible: solo gas natural\\n            equipo:testo\\n            selección de sitio de muestreo:motores reciprocantes,calderos y hornos de proceso\", \"methodologie\": {\"id\": 567, \"info\": null, \"created_at\": \"2026-04-11 13:06:00\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T23:06:00.000000Z\", \"description\": \"EPA CTM-030\\n                    Determination of Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas-Fired Engines, Boilers and Process Heaters Using Portable Analyzers. 1997\"}, \"number_samples\": 1, \"frequency_label\": \"Mensual (cada mes)\", \"methodologie_id\": 567}', 'matriz', 1, 350.00, 350.00, '2026-04-11 18:21:20', '2026-04-11 23:21:20', NULL);
INSERT INTO `items_quotes` VALUES (2, 1, 'App\\Models\\tenant\\Matriz', 309, '{\"bg\": \"bg-teal-50\", \"id\": 309, \"price\": 350, \"essays\": [{\"id\": 762, \"lcm\": \"2,05\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Nitrogen Oxides (NOx) ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 763, \"lcm\": \"1,34\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Nitric Oxide (NO) ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 764, \"lcm\": \"0,21\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Nitrogen Dioxide (NO2) ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}], \"frequency\": \"monthly\", \"created_at\": \"2026-04-11 13:06:00\", \"unit_price\": \"350.00\", \"updated_at\": \"2026-04-11T23:06:00.000000Z\", \"description\": \"todo tipo de combustible equipo:testo\", \"methodologie\": {\"id\": 570, \"info\": null, \"created_at\": \"2026-04-11 13:06:00\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T23:06:00.000000Z\", \"description\": \"EPA CTM-022\\n                    Determination of Nitric Oxide, Nitrogen Dioxide\\n                    and NOx Emissions from Stationary Combustion Sources by electrochemical analyzer. 1995\"}, \"number_samples\": 1, \"frequency_label\": \"Mensual (cada mes)\", \"methodologie_id\": 570}', 'matriz', 1, 350.00, 350.00, '2026-04-11 18:21:20', '2026-04-11 23:21:20', NULL);
INSERT INTO `items_quotes` VALUES (3, 1, 'App\\Models\\tenant\\Matriz', 308, '{\"bg\": \"bg-cyan-50\", \"id\": 308, \"price\": 600, \"essays\": [{\"id\": 723, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Benzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 724, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Trichloroethene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 725, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Toluene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 726, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Tetrachloroethene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 727, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Chlorobenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 728, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Ethylbenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 729, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- m-Xylene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 730, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- P-Xylene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 731, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- o-Xylene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 732, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- m,p-Xylene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 733, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Styrene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 734, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Isopropylbenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 735, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Bromobenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 736, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- n-Propylbenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 737, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 2- Chlorotoluene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 738, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 4-Chlorotoluene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 739, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 1,3,5- Trimethylbenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 740, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Tert- Butylbenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 741, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 1,2,4-Trimethylbenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 742, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Sec-Butylbenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 743, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 1,3- Dichlorobenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 744, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 1,4- Dichlorobenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 745, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- p- Isopropyltoluene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 746, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 1,2-Dichlorobenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 747, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- nButhylbenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 748, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 1,2,4- Trichlorobenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 749, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- Naphthalene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 750, \"lcm\": \"0,3333\", \"condition\": {\"id\": 20, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS\"}, \"description\": \"- 1,2,3-Trichlorobenzene ²\", \"condition_id\": 20, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}], \"frequency\": \"bimonthly\", \"created_at\": \"2026-04-11 13:06:00\", \"unit_price\": \"600.00\", \"updated_at\": \"2026-04-11T23:06:00.000000Z\", \"description\": \"VOCS Equipo:Isocinetico pequeño\", \"methodologie\": {\"id\": 564, \"info\": null, \"created_at\": \"2026-04-11 13:06:00\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T23:06:00.000000Z\", \"description\": \"EPA Method 18 (7-1-23)\\n                    Measurement of Gaseous Organic Compound Emissions by Gas Chromatography\\n                    NTP 900.018:2021 \\n                    ATMOSPHERIC EMISSIONS MONITORING.Measurement of Gaseous Organic Compound Emissions by Gas Chromatography\"}, \"number_samples\": 1, \"frequency_label\": \"Bimestral (cada 2 meses)\", \"methodologie_id\": 564}', 'matriz', 1, 600.00, 600.00, '2026-04-11 18:21:20', '2026-04-11 23:21:20', NULL);
INSERT INTO `items_quotes` VALUES (4, 1, 'App\\Models\\tenant\\Matriz', 297, '{\"bg\": \"bg-cyan-50\", \"id\": 297, \"price\": 20, \"essays\": [{\"id\": 563, \"lcm\": \"3\", \"condition\": {\"id\": 19, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE INACAL\"}, \"description\": \"Sulfato\", \"condition_id\": 19, \"units_measurement\": {\"id\": 89, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/L\"}, \"units_measurement_id\": 89}], \"frequency\": \"bimonthly\", \"created_at\": \"2026-04-11 11:48:03\", \"unit_price\": \"20.00\", \"updated_at\": \"2026-04-11T21:48:03.000000Z\", \"description\": \"Agua \\nAniones\", \"methodologie\": {\"id\": 429, \"info\": null, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"Sulfate. Turbidimetric Method                                                                                 SMEWW-APHA-AWWA-WEF Part 4500-SO4= E, 24th Ed. (2023)\"}, \"number_samples\": 1, \"frequency_label\": \"Bimestral (cada 2 meses)\", \"methodologie_id\": 429}', 'matriz', 1, 20.00, 20.00, '2026-04-11 18:21:20', '2026-04-11 23:21:20', NULL);
INSERT INTO `items_quotes` VALUES (5, 1, 'App\\Models\\tenant\\Matriz', 299, '{\"bg\": \"bg-cyan-50\", \"id\": 299, \"price\": 350, \"essays\": [{\"id\": 689, \"lcm\": \"1,25\", \"condition\": {\"id\": 19, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE INACAL\"}, \"description\": \"- Monóxido de Carbono (CO).\", \"condition_id\": 19, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 690, \"lcm\": \"1,34\", \"condition\": {\"id\": 19, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE INACAL\"}, \"description\": \"- Monóxido de Nitrógeno (NO).\", \"condition_id\": 19, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 691, \"lcm\": \"0,21\", \"condition\": {\"id\": 19, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE INACAL\"}, \"description\": \"- Dioxido de Nitrogeno (NO2).\", \"condition_id\": 19, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}, {\"id\": 692, \"lcm\": \"0-01\", \"condition\": {\"id\": 19, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE INACAL\"}, \"description\": \"- Oxígeno (O2).\", \"condition_id\": 19, \"units_measurement\": {\"id\": 82, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"%\"}, \"units_measurement_id\": 82}, {\"id\": 693, \"lcm\": \"2,05\", \"condition\": {\"id\": 19, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE INACAL\"}, \"description\": \"- Oxido de Nitrógeno (NOx).\", \"condition_id\": 19, \"units_measurement\": {\"id\": 113, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/Nm3\"}, \"units_measurement_id\": 113}], \"frequency\": \"bimonthly\", \"created_at\": \"2026-04-11 13:06:00\", \"unit_price\": \"350.00\", \"updated_at\": \"2026-04-11T23:06:00.000000Z\", \"description\": \"EPA Method 8270E Rev 6 Jun 2018. // EPA Method 3550C Rev 3 February 2007\\n                Semi-volatile Organic Compounds by Gas Chromatography/Mass Spectrometry // Ultrasonic Extraction\", \"methodologie\": {\"id\": 509, \"info\": null, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"Determination of Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas-Fired Engines, Boilers and Process Heaters Using Portable Analyzers  CTM 034 (1997)\"}, \"number_samples\": 1, \"frequency_label\": \"Bimestral (cada 2 meses)\", \"methodologie_id\": 509}', 'matriz', 1, 350.00, 350.00, '2026-04-11 18:21:20', '2026-04-11 23:21:20', NULL);
INSERT INTO `items_quotes` VALUES (6, 1, 'App\\Models\\tenant\\Matriz', 285, '{\"bg\": \"bg-cyan-50\", \"id\": 285, \"price\": 250, \"essays\": [{\"id\": 883, \"lcm\": \"0,000001\", \"condition\": {\"id\": 26, \"info\": null, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"IAS(*)\"}, \"description\": \"Bifenilos Policlorados (PCB\", \"condition_id\": 26, \"units_measurement\": {\"id\": 89, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/L\"}, \"units_measurement_id\": 89}], \"frequency\": \"bimonthly\", \"created_at\": \"2026-04-11 11:19:49\", \"unit_price\": \"250.00\", \"updated_at\": \"2026-04-11T21:19:49.000000Z\", \"description\": \"Agua\", \"methodologie\": {\"id\": 553, \"info\": null, \"created_at\": \"2026-04-11 07:57:33\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:33.000000Z\", \"description\": \"Polychlorinated Biphenyls (PCBs) by Gas Chromatography Separatory funnel liquid-liquid extraction.EPA Method 8082A Revision 1 February 2007 // EPA Method 3510C Revision 3 December 1996(Cromatografía de Gases con detector de captura de electrones (ECD))\"}, \"number_samples\": 1, \"frequency_label\": \"Bimestral (cada 2 meses)\", \"methodologie_id\": 553}', 'matriz', 1, 250.00, 250.00, '2026-04-11 18:21:20', '2026-04-11 23:21:20', NULL);
INSERT INTO `items_quotes` VALUES (7, 1, 'App\\Models\\tenant\\Matriz', 289, '{\"id\": 289, \"price\": 50, \"essays\": [{\"id\": 601, \"lcm\": \"1.8\", \"condition\": {\"id\": 23, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE IAS(*)\"}, \"description\": \"Coliformes fecales o termotolerantes\", \"condition_id\": 23, \"units_measurement\": {\"id\": 99, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"NMP/100mL\"}, \"units_measurement_id\": 99}], \"created_at\": \"2026-04-11 11:19:49\", \"unit_price\": \"50.00\", \"updated_at\": \"2026-04-11T21:19:49.000000Z\", \"description\": \"Agua\", \"methodologie\": {\"id\": 556, \"info\": null, \"created_at\": \"2026-04-11 07:57:33\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:33.000000Z\", \"description\": \"Enumeration of Fecal Coliforms by MPN method Fecal Coliform ProcedureSM 9221 E. / 9221C. Standard Methods 23rd Edition 2017\"}, \"number_samples\": 1, \"methodologie_id\": 556}', 'matriz', 1, 50.00, 50.00, '2026-04-11 18:21:20', '2026-04-11 23:21:20', NULL);
INSERT INTO `items_quotes` VALUES (8, 1, 'App\\Models\\tenant\\Matriz', 293, '{\"id\": 293, \"price\": 40, \"essays\": [{\"id\": 559, \"lcm\": \"0.05\", \"condition\": {\"id\": 19, \"info\": null, \"created_at\": \"2026-04-11 07:57:31\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:31.000000Z\", \"description\": \"ACREDITADO ANTE INACAL\"}, \"description\": \"Fluoruro\", \"condition_id\": 19, \"units_measurement\": {\"id\": 89, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"mg/L\"}, \"units_measurement_id\": 89}], \"created_at\": \"2026-04-11 11:48:03\", \"unit_price\": \"40.00\", \"updated_at\": \"2026-04-11T21:48:03.000000Z\", \"description\": \"Agua \\nAniones\", \"methodologie\": {\"id\": 425, \"info\": null, \"created_at\": \"2026-04-11 07:57:32\", \"deleted_at\": null, \"updated_at\": \"2026-04-11T17:57:32.000000Z\", \"description\": \"Análisis de Aguas - Determinación de fluroruros en aguas naturales, residuales y residuales tratadas                                                                        NMX-AA-077-SCFI-2001 (2001)\"}, \"number_samples\": 1, \"methodologie_id\": 425}', 'matriz', 1, 40.00, 40.00, '2026-04-11 18:21:20', '2026-04-11 23:21:20', NULL);

-- ----------------------------
-- Table structure for laboratory_analysis
-- ----------------------------
DROP TABLE IF EXISTS `laboratory_analysis`;
CREATE TABLE `laboratory_analysis`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of laboratory_analysis
-- ----------------------------

-- ----------------------------
-- Table structure for logistic_cats
-- ----------------------------
DROP TABLE IF EXISTS `logistic_cats`;
CREATE TABLE `logistic_cats`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `unit_price` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of logistic_cats
-- ----------------------------

-- ----------------------------
-- Table structure for matriz
-- ----------------------------
DROP TABLE IF EXISTS `matriz`;
CREATE TABLE `matriz`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `information` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `type_matriz` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `methodologie_id` bigint UNSIGNED NULL DEFAULT NULL,
  `number_samples` int NULL DEFAULT NULL,
  `unit_price` decimal(10, 2) NULL DEFAULT NULL,
  `price` decimal(10, 2) NULL DEFAULT NULL,
  `other_company` tinyint NULL DEFAULT 0,
  `other_company_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `other_company_price` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `matriz_methodologie_id_foreign`(`methodologie_id` ASC) USING BTREE,
  CONSTRAINT `matriz_methodologie_id_foreign` FOREIGN KEY (`methodologie_id`) REFERENCES `methodologies` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 311 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of matriz
-- ----------------------------
INSERT INTO `matriz` VALUES (49, NULL, NULL, 'Aire', NULL, 373, 1, 220.00, 0.00, 0, NULL, NULL, '2026-04-11 14:08:51', '2026-04-11 19:08:51', NULL);
INSERT INTO `matriz` VALUES (50, NULL, NULL, 'Aire', NULL, 374, 1, 220.00, 0.00, 0, NULL, NULL, '2026-04-11 14:10:27', '2026-04-11 19:10:27', NULL);
INSERT INTO `matriz` VALUES (51, NULL, NULL, 'Aire', NULL, 375, 1, 220.00, 0.00, 0, NULL, NULL, '2026-04-11 14:11:10', '2026-04-11 19:11:10', NULL);
INSERT INTO `matriz` VALUES (52, NULL, NULL, 'Aire', NULL, 376, 1, 220.00, 0.00, 0, NULL, NULL, '2026-04-11 14:11:57', '2026-04-11 19:11:57', NULL);
INSERT INTO `matriz` VALUES (53, NULL, NULL, 'Metales de Bajo Volumen (PM10, PM2.5)', NULL, 377, 1, 120.00, 0.00, 0, NULL, NULL, '2026-04-11 14:19:49', '2026-04-11 19:19:49', NULL);
INSERT INTO `matriz` VALUES (54, NULL, NULL, 'Metales de Alto Volumen (PTS, PM10, PM2.5)', NULL, 378, 1, 120.00, 0.00, 0, NULL, NULL, '2026-04-11 14:28:02', '2026-04-11 19:28:02', NULL);
INSERT INTO `matriz` VALUES (55, NULL, NULL, 'Metales de Alto Volumen (PM10)', NULL, 379, 1, 150.00, 0.00, 0, NULL, NULL, '2026-04-11 14:42:39', '2026-04-11 19:42:39', NULL);
INSERT INTO `matriz` VALUES (56, NULL, NULL, 'Parámetros metereológicos', NULL, 380, 1, 100.00, 0.00, 0, NULL, NULL, '2026-04-11 14:44:47', '2026-04-11 19:44:47', NULL);
INSERT INTO `matriz` VALUES (57, NULL, NULL, 'Suelo', NULL, 452, 1, 250.00, 0.00, 0, NULL, NULL, '2026-04-11 15:30:56', '2026-04-11 20:30:56', NULL);
INSERT INTO `matriz` VALUES (58, NULL, NULL, 'Suelo', NULL, 455, 1, 250.00, 0.00, 0, NULL, NULL, '2026-04-11 16:00:35', '2026-04-11 21:00:35', NULL);
INSERT INTO `matriz` VALUES (59, NULL, NULL, 'Aire', NULL, 378, 1, 120.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (60, NULL, NULL, 'Aire', NULL, 379, 1, 150.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (61, NULL, NULL, 'Aire', NULL, 381, 1, 65.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (62, NULL, NULL, 'Aire', NULL, 382, 1, 65.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (63, NULL, NULL, 'Aire', NULL, 383, 1, 65.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (64, NULL, NULL, 'Aire', NULL, 384, 1, 65.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (65, NULL, NULL, 'Aire', NULL, 385, 1, 65.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (66, NULL, NULL, 'Aire', NULL, 386, 1, 65.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (67, NULL, NULL, 'Aire', NULL, 387, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (68, NULL, NULL, 'Aire', NULL, 388, 1, 150.00, NULL, 1, 'typsa', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (69, NULL, NULL, 'Aire', NULL, 389, 1, 2500.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (70, NULL, NULL, 'Aire', NULL, 390, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (71, NULL, NULL, 'Aire\nMétodo automático', NULL, 391, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (72, NULL, NULL, 'Aire\nMétodo automático', NULL, 392, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (73, NULL, NULL, 'Aire\nMétodo automático', NULL, 393, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (74, NULL, NULL, 'Aire\nMétodo automático', NULL, 394, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (75, NULL, NULL, 'Aire\nMétodo automático', NULL, 395, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (76, NULL, NULL, 'Aire\nMétodo automático', NULL, 396, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (77, NULL, NULL, 'Aire\nMétodo automático', NULL, 397, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (78, NULL, NULL, 'Aire\nMétodo automático', NULL, 398, 1, 300.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (79, NULL, NULL, 'Agua', NULL, 399, 1, 12.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (80, NULL, NULL, 'Agua', NULL, 400, 1, 16.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (81, NULL, NULL, 'Agua', NULL, 401, 1, 8.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (82, NULL, NULL, 'Agua', NULL, 402, 1, 8.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (83, NULL, NULL, 'Agua', NULL, 403, 1, 25.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (84, NULL, NULL, 'Solo aplica para Agua Subterránea', NULL, 404, 1, 100.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (85, NULL, NULL, 'Agua', NULL, 405, 1, 30.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (86, NULL, NULL, 'Agua', NULL, 406, 1, 60.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (87, NULL, NULL, 'Agua', NULL, 406, 1, 60.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (88, NULL, NULL, 'Agua', NULL, 407, 1, 60.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (89, NULL, NULL, 'Agua', NULL, 408, 1, 60.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (90, NULL, NULL, 'Agua', NULL, 409, 1, 120.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (91, NULL, NULL, 'Solo para Agua natural, Agua residual, Agua salina, Agua de proceso (Medición de campo)', NULL, 410, 1, 120.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (92, NULL, NULL, 'Agua', NULL, 411, 1, 30.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (93, NULL, NULL, 'Agua', NULL, 411, 1, 30.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (94, NULL, NULL, 'Agua Potable Anexo ll', NULL, 412, 1, 50.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (95, NULL, NULL, 'Agua Potable Anexo ll', NULL, 413, 1, 50.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (96, NULL, NULL, 'Agua', NULL, 414, 1, 33.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (97, NULL, NULL, 'Agua', NULL, 415, 1, 18.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (98, NULL, NULL, 'Agua', NULL, 416, 1, 20.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (99, NULL, NULL, 'Agua', NULL, 416, 1, 20.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (100, NULL, NULL, 'Agua', NULL, 416, 1, 20.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (101, NULL, NULL, 'Agua', NULL, 417, 1, 120.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (102, NULL, NULL, 'Agua', NULL, 418, 1, 38.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (103, NULL, NULL, 'Agua', NULL, 419, 1, 35.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (104, NULL, NULL, 'Agua', NULL, 420, 1, 35.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (105, NULL, NULL, 'Agua', NULL, 421, 1, 21.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (106, NULL, NULL, 'Agua', NULL, 422, 1, 40.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (107, NULL, NULL, 'Agua', NULL, 423, 1, 22.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (108, NULL, NULL, 'Agua', NULL, 424, 1, 25.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (109, NULL, NULL, 'Agua', NULL, 430, 1, 23.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (110, NULL, NULL, 'Agua', NULL, 431, 1, 80.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (111, NULL, NULL, 'Agua', NULL, 432, 1, 48.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (112, NULL, NULL, 'Agua', NULL, 433, 1, 45.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (113, NULL, NULL, 'Solo en Agua Salina', NULL, 434, 1, 45.00, NULL, 0, '', NULL, '2026-04-11 16:19:47', '2026-04-11 21:19:47', NULL);
INSERT INTO `matriz` VALUES (114, NULL, NULL, 'Agua', NULL, 435, 1, 24.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (115, NULL, NULL, 'Agua', NULL, 436, 1, 22.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (116, NULL, NULL, 'Agua', NULL, 437, 1, 120.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (117, NULL, NULL, 'Agua', NULL, 430, 1, 23.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (118, NULL, NULL, 'Agua', NULL, 438, 1, 22.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (119, NULL, NULL, 'Agua', NULL, 439, 1, 22.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (120, NULL, NULL, 'Agua', NULL, 440, 1, 22.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (121, NULL, NULL, 'Agua', NULL, 441, 1, 22.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (122, NULL, NULL, 'Agua', NULL, 442, 1, 48.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (123, NULL, NULL, 'Agua', NULL, 430, 1, 23.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (124, NULL, NULL, 'Agua', NULL, 430, 1, 23.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (125, NULL, NULL, 'Agua', NULL, 443, 1, 240.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (126, NULL, NULL, 'Calidad de Agua', NULL, 444, 1, 200.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (127, NULL, NULL, 'Calidad de Agua', NULL, 445, 1, 140.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (128, NULL, NULL, 'Calidad de Agua', NULL, 445, 1, 140.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (129, NULL, NULL, 'Calidad de Agua', NULL, 446, 1, 40.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (130, NULL, NULL, 'Calidad de Agua', NULL, 447, 1, 40.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (131, NULL, NULL, 'Calidad de Agua', NULL, 448, 1, 140.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (132, NULL, NULL, 'Agua Salina y Agua de proceso', NULL, 449, 1, 140.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (133, NULL, NULL, 'Agua', NULL, 450, 1, 40.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (134, NULL, NULL, 'Agua', NULL, 430, 1, 23.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (135, NULL, NULL, 'Agua', NULL, 451, 2, 80.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (136, NULL, NULL, 'Agua', NULL, 443, 1, 240.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (137, NULL, NULL, 'Agua', NULL, 452, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (138, NULL, NULL, 'Agua', NULL, 453, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (139, NULL, NULL, 'Agua', NULL, 454, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (140, NULL, NULL, 'Agua', NULL, 455, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (141, NULL, NULL, 'Agua', NULL, 456, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (142, NULL, NULL, 'Agua', NULL, 457, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (143, NULL, NULL, 'Agua', NULL, 458, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (144, NULL, NULL, 'Agua', NULL, 459, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (145, NULL, NULL, 'Agua', NULL, 460, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (146, NULL, NULL, 'Agua', NULL, 461, 1, 600.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (147, NULL, NULL, 'Agua', NULL, 462, 1, 600.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (148, NULL, NULL, 'Agua', NULL, 463, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (149, NULL, NULL, 'Agua', NULL, 464, 1, 50.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (150, NULL, NULL, 'Agua', NULL, 465, 1, 50.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (151, NULL, NULL, 'Agua', NULL, 466, 1, 70.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (152, NULL, NULL, 'Agua', NULL, 467, 1, 150.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (153, NULL, NULL, 'Agua', NULL, 468, 1, 120.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (154, NULL, NULL, 'Agua', NULL, 469, 1, 300.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (155, NULL, NULL, 'Agua', NULL, 470, 2, 150.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (156, NULL, NULL, 'Agua', NULL, 471, 1, 80.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (157, NULL, NULL, 'Agua', NULL, 472, 1, 120.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (158, NULL, NULL, 'Agua', NULL, 473, 1, 121.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (159, NULL, NULL, 'Agua', NULL, 474, 1, 120.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (160, NULL, NULL, 'Agua', NULL, 475, 1, 120.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (161, NULL, NULL, 'Agua', NULL, 476, 1, 50.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (162, NULL, NULL, 'Agua', NULL, 477, 1, 50.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (163, NULL, NULL, 'Agua', NULL, 478, 1, 50.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (164, NULL, NULL, 'Monitoreo Hidrobiológico', NULL, 479, 1, 170.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (165, NULL, NULL, 'Monitoreo Hidrobiológico', NULL, 480, 1, 170.00, NULL, 1, 'ENVIROTEST', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (166, NULL, NULL, 'Sedimentos epicontinentales', NULL, 481, 1, 170.00, NULL, 1, 'QUIMPETROL', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (167, NULL, NULL, 'Suelo', NULL, 482, 1, 60.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (168, NULL, NULL, 'Suelo', NULL, 483, 1, 55.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (169, NULL, NULL, 'LODO\nSEDIMENTO\nSUELO', NULL, 484, 1, 140.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (170, NULL, NULL, 'Suelo', NULL, 485, 1, 150.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (171, NULL, NULL, 'Suelo', NULL, 485, 1, 150.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (172, NULL, NULL, 'Suelo', NULL, 485, 1, NULL, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (173, NULL, NULL, 'Suelo', NULL, 490, 1, 150.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (174, NULL, NULL, 'Suelo', NULL, 485, 1, 150.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (175, NULL, NULL, 'Suelo', NULL, 485, 1, NULL, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (176, NULL, NULL, 'Suelo', NULL, 491, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (177, NULL, NULL, 'Suelo', NULL, 482, 1, 60.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (178, NULL, NULL, 'Suelo', NULL, 483, 1, 55.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (179, NULL, NULL, 'LODO\nSEDIMENTO\nSUELO', NULL, 494, 1, 140.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (180, NULL, NULL, 'Ruido Ambiental', NULL, 495, 1, 120.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (181, NULL, NULL, 'Ruido Ambiental', NULL, 495, 1, 150.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (182, NULL, NULL, 'Ruido Ambiental', NULL, 495, 1, 100.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (183, NULL, NULL, 'Ruido Ambiental', NULL, 495, 1, 120.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (184, NULL, NULL, 'Vibración', NULL, 496, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (185, NULL, NULL, '(SSO)', NULL, 497, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (186, NULL, NULL, 'Radiación no ionizane', NULL, 498, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (187, NULL, NULL, 'Radiación no ionizane', NULL, 499, 1, 300.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (188, NULL, NULL, '(SSO)', NULL, 500, 1, 100.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (189, NULL, NULL, '(SSO)', NULL, 501, 1, 100.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (190, NULL, NULL, 'Field collection + Lab Testing\nAIR / FILTER (Only Lab Testing)', NULL, 502, 1, 150.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (191, NULL, NULL, 'Field collection + Lab Testing\nAIR / FILTER (Only Lab Testing)', NULL, 503, 1, 150.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (192, NULL, NULL, 'Aire (SSO)', NULL, 504, 1, 50.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (193, NULL, NULL, 'Aire (SSO)', NULL, 505, 1, 50.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (194, NULL, NULL, '(SSO)', NULL, 506, 1, 20.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (195, NULL, NULL, '(SSO)', NULL, 507, 1, 100.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (196, NULL, NULL, '(SSO)', NULL, 507, 1, 150.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (197, NULL, NULL, '(SSO)', NULL, 508, 1, 120.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (198, NULL, NULL, 'Emisiones\nGas Natural  y (GLP) disel (butano y propano)', NULL, 509, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (199, NULL, NULL, 'Tipo de combustible:todos\nEquipo: Testo', NULL, 510, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (200, NULL, NULL, 'Emisiones_ para todos los combustibles', NULL, 511, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (201, NULL, NULL, 'Emisiones\ncon Testo (cálculo)', NULL, 512, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (202, NULL, NULL, 'Monitoreo especificamente  en pesqueras \nEquipo:Testo', NULL, 515, 1, 400.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (203, NULL, NULL, 'Para todo tipo de combustible\nEquipo:Isocinetico pequeño', NULL, 516, 1, 400.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (204, NULL, NULL, 'tipo de combustible: (biomasa)\nEquipo:Isocinetico pequeño', NULL, 517, 1, 400.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (205, NULL, NULL, 'tipo de combustible:biomasa, fuentes de proceso\nEquipo:Isocinetico pequeño', NULL, 518, 1, 400.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (206, NULL, NULL, 'Tipo de combustible:de combustión \nEquipo: Testo', NULL, 519, 1, 400.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (207, NULL, NULL, 'Emisiones', NULL, 521, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (208, NULL, NULL, 'Emisiones', NULL, 522, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (209, NULL, NULL, 'Emisiones', NULL, 523, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (210, NULL, NULL, 'Emisiones', NULL, 524, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (211, NULL, NULL, 'Emisiones', NULL, 525, 1, 500.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (212, NULL, NULL, 'Emisiones', NULL, 526, 1, 500.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (213, NULL, NULL, 'Emisiones', NULL, 561, 1, NULL, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (214, NULL, NULL, 'tipo de combustible:biomasa \nequipo:testo', NULL, 528, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (215, NULL, NULL, 'Tipo de combustible:todos\nEquipo: Testo', NULL, 510, 1, 300.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (216, NULL, NULL, 'Emisiones\ncon equipo isocinético', NULL, 534, 1, 1200.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (217, NULL, NULL, 'Emisiones_ para todos los combustibles', NULL, 511, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (218, NULL, NULL, 'Emisiones\ncon Testo (cálculo)', NULL, 512, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (219, NULL, NULL, 'Monitoreo especificamente  en pesqueras \nEquipo:Testo', NULL, 515, 1, 400.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (220, NULL, NULL, 'Para todo tipo de combustible\nEquipo:Testo', NULL, 516, 1, 400.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (221, NULL, NULL, 'tipo de combustible: (biomasa)\nEquipo:Isocinetico pequeño', NULL, 517, 1, 400.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (222, NULL, NULL, 'tipo de combustible:biomasa, fuentes de proceso\nEquipo:Isocinetico pequeño', NULL, 518, 1, 400.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (223, NULL, NULL, 'Tipo de combustible:de combustión \nEquipo: Testo', NULL, 519, 1, 400.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (224, NULL, NULL, 'Emisiones', NULL, 521, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (225, NULL, NULL, 'Emisiones', NULL, 522, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (226, NULL, NULL, 'Emisiones', NULL, 523, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (227, NULL, NULL, 'Emisiones', NULL, 524, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (228, NULL, NULL, 'Emisiones', NULL, 525, 1, 500.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (229, NULL, NULL, 'Emisiones', NULL, 526, 1, 500.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (230, NULL, NULL, 'Emisiones', NULL, 561, 1, NULL, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (231, NULL, NULL, 'es un método de la EPA (Environmental Protection Agency) utilizado para calcular el peso molecular seco (dry molecular weight) de una muestra de gas, generalmente en emisiones de fuentes estacionarias como chimeneas o ductos industriales.', NULL, 527, 1, 500.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (232, NULL, NULL, 'tipo de combustible:biomasa \nequipo:testo', NULL, 528, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (233, NULL, NULL, 'todo tipo de combustible \nequipo:testo', NULL, 529, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (234, NULL, NULL, 'tipo de combustible: solo gas natural\nequipo:testo\nselección de sitio de muestreo:motores reciprocantes,calderos y hornos de proceso', NULL, 530, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (235, NULL, NULL, 'tipo de combustible: solo gas natural\nequipo:testo', NULL, 531, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (236, NULL, NULL, 'tipo de combustible: solo gas natural\nequipo:testo', NULL, 532, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (237, NULL, NULL, 'tipo de combustible: solo gas natural(propano,butano y combustibles líquidos)\nequipo:testo', NULL, 533, 1, 350.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (238, NULL, NULL, 'Agua Potable', NULL, 535, 3, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (239, NULL, NULL, 'Agua Potable', NULL, 536, 3, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (240, NULL, NULL, 'Agua Potable', NULL, 537, 3, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (241, NULL, NULL, 'Agua Potable', NULL, 538, 3, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (242, NULL, NULL, 'Agua Potable', NULL, 539, 3, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (243, NULL, NULL, 'Agua Potable', NULL, 540, 3, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (244, NULL, NULL, 'Agua Potable', NULL, 541, 3, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (245, NULL, NULL, 'Agua Potable', NULL, 542, 3, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (246, NULL, NULL, 'Agua Potable', NULL, 543, 3, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (247, NULL, NULL, 'Agua Potable Anexo l', NULL, 464, 4, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (248, NULL, NULL, 'Agua Potable Anexo l', NULL, 466, 4, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (249, NULL, NULL, 'Agua Potable Anexo l', NULL, 465, 4, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (250, NULL, NULL, 'Agua Potable Anexo l', NULL, 471, 4, 80.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (251, NULL, NULL, 'Agua Potable Anexo l', NULL, 470, 4, 300.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (252, NULL, NULL, 'Agua Potable Anexo l', NULL, 467, 4, 300.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (253, NULL, NULL, 'Agua Potable Anexo l', NULL, 468, 4, 300.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (254, NULL, NULL, 'Agua Potable Anexo l', NULL, 469, 4, 300.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (255, NULL, NULL, 'Agua Potable Anexo ll', NULL, 412, 3, 180.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (256, NULL, NULL, 'Agua Potable Anexo ll', NULL, 413, 3, 180.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (257, NULL, NULL, 'Agua Potable Anexo ll', NULL, 542, 3, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (258, NULL, NULL, 'Agua Potable Anexo ll', NULL, 536, 3, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (259, NULL, NULL, 'Agua Potable Anexo ll', NULL, 538, 3, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (260, NULL, NULL, 'Agua Potable Anexo ll', NULL, 537, 3, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (261, NULL, NULL, 'Agua Potable Anexo ll', NULL, 540, 3, 0.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (262, NULL, NULL, 'Agua Potable Anexo ll', NULL, 544, 3, 50.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (263, NULL, NULL, 'Agua Potable Anexo ll', NULL, 545, 3, 50.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (264, NULL, NULL, 'Agua Potable Anexo ll', NULL, 424, 3, 60.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (265, NULL, NULL, 'Agua Potable Anexo ll', NULL, 546, 3, 50.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (266, NULL, NULL, 'Agua Potable Anexo ll', NULL, 547, 3, 120.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (267, NULL, NULL, 'Agua', NULL, 414, 1, 33.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (268, NULL, NULL, 'Agua', NULL, 416, 1, 20.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (269, NULL, NULL, 'Agua', NULL, 420, 1, 35.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (270, NULL, NULL, 'Agua', NULL, 421, 1, 21.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (271, NULL, NULL, 'Agua', NULL, 548, 1, 40.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (272, NULL, NULL, 'Agua', NULL, 399, 1, 12.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (273, NULL, NULL, 'Agua', NULL, 549, 1, 48.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (274, NULL, NULL, 'Agua', NULL, 550, 1, 45.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (275, NULL, NULL, 'Agua', NULL, 551, 1, 60.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (276, NULL, NULL, 'Agua', NULL, 435, 1, 24.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (277, NULL, NULL, 'Agua', NULL, 425, 1, 40.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (278, NULL, NULL, 'Agua', NULL, 552, 1, 120.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (279, NULL, NULL, 'Agua', NULL, 428, 1, 22.00, NULL, 0, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `matriz` VALUES (280, NULL, NULL, 'Agua', NULL, 400, 1, 16.00, NULL, 0, '', NULL, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `matriz` VALUES (281, NULL, NULL, 'Agua', NULL, 401, 1, 8.00, NULL, 0, '', NULL, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `matriz` VALUES (282, NULL, NULL, 'Agua', NULL, 429, 1, 20.00, NULL, 0, '', NULL, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `matriz` VALUES (283, NULL, NULL, 'Agua', NULL, 402, 1, 8.00, NULL, 0, '', NULL, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `matriz` VALUES (284, NULL, NULL, 'Agua', NULL, 445, 1, 140.00, NULL, 0, '', NULL, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `matriz` VALUES (285, NULL, NULL, 'Agua', NULL, 553, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `matriz` VALUES (286, NULL, NULL, 'Agua', NULL, 554, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `matriz` VALUES (287, NULL, NULL, 'Agua', NULL, 554, 1, 250.00, NULL, 0, '', NULL, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `matriz` VALUES (288, NULL, NULL, 'Agua', NULL, 555, 1, 400.00, NULL, 0, '', NULL, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `matriz` VALUES (289, NULL, NULL, 'Agua', NULL, 556, 1, 50.00, NULL, 0, '', NULL, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `matriz` VALUES (290, NULL, NULL, 'Agua', NULL, 557, 1, 70.00, NULL, 0, '', NULL, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `matriz` VALUES (291, NULL, NULL, 'Agua', NULL, 558, 1, 70.00, NULL, 0, '', NULL, '2026-04-11 16:19:49', '2026-04-11 21:19:49', NULL);
INSERT INTO `matriz` VALUES (292, NULL, NULL, 'Agua \nAniones', NULL, 421, 1, 21.00, NULL, 0, '', NULL, '2026-04-11 16:48:03', '2026-04-11 21:48:03', NULL);
INSERT INTO `matriz` VALUES (293, NULL, NULL, 'Agua \nAniones', NULL, 425, 1, 40.00, NULL, 0, '', NULL, '2026-04-11 16:48:03', '2026-04-11 21:48:03', NULL);
INSERT INTO `matriz` VALUES (294, NULL, NULL, 'Agua \nAniones', NULL, 426, 1, 22.00, NULL, 0, '', NULL, '2026-04-11 16:48:03', '2026-04-11 21:48:03', NULL);
INSERT INTO `matriz` VALUES (295, NULL, NULL, 'Agua \nAniones', NULL, 427, 1, 22.00, NULL, 0, '', NULL, '2026-04-11 16:48:03', '2026-04-11 21:48:03', NULL);
INSERT INTO `matriz` VALUES (296, NULL, NULL, 'Agua \nAniones', NULL, 428, 1, 22.00, NULL, 0, '', NULL, '2026-04-11 16:48:03', '2026-04-11 21:48:03', NULL);
INSERT INTO `matriz` VALUES (297, NULL, NULL, 'Agua \nAniones', NULL, 429, 1, 20.00, NULL, 0, '', NULL, '2026-04-11 16:48:03', '2026-04-11 21:48:03', NULL);
INSERT INTO `matriz` VALUES (298, NULL, NULL, 'Suelo', NULL, 562, 1, 250.00, NULL, 0, NULL, NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `matriz` VALUES (299, NULL, NULL, 'EPA Method 8270E Rev 6 Jun 2018. // EPA Method 3550C Rev 3 February 2007\n                Semi-volatile Organic Compounds by Gas Chromatography/Mass Spectrometry // Ultrasonic Extraction', NULL, 509, 1, 350.00, NULL, 0, NULL, NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `matriz` VALUES (300, NULL, NULL, 'Metales totales en emisiones gaseosas', NULL, 563, 1, 500.00, NULL, 0, NULL, NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `matriz` VALUES (301, NULL, NULL, 'Equipo:Isocinetico pequeño', NULL, 564, 1, 600.00, NULL, 0, NULL, NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `matriz` VALUES (302, NULL, NULL, 'Es un método de la EPA (Environmental Protection Agency) utilizado para calcular el peso molecular seco (dry molecular weight) de una muestra de gas, generalmente en emisiones de fuentes estacionarias como chimeneas o ductos industriales.', NULL, 565, 1, 500.00, NULL, 0, NULL, NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `matriz` VALUES (303, NULL, NULL, 'Todo tipo de combustible equipo:testo', NULL, 566, 1, 350.00, NULL, 0, NULL, NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `matriz` VALUES (304, NULL, NULL, 'EPA CTM-022\n            Determination of Nitric Oxide, Nitrogen Dioxide\n            and NOx Emissions from Stationary Combustion Sources by electrochemical analyzer. 1995', NULL, 567, 1, 350.00, NULL, 0, NULL, NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `matriz` VALUES (305, NULL, NULL, 'tipo de combustible: solo gas natural equipo:testo', NULL, 568, 1, 350.00, NULL, 0, NULL, NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `matriz` VALUES (306, NULL, NULL, 'tipo de combustible: solo gas natural(propano,butano y combustibles líquidos) equipo:testo', NULL, 569, 1, 350.00, NULL, 0, NULL, NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `matriz` VALUES (307, NULL, NULL, 'Emisiones Gas Natural y disel (butano y propano)', NULL, 509, 1, 350.00, NULL, 0, NULL, NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `matriz` VALUES (308, NULL, NULL, 'VOCS Equipo:Isocinetico pequeño', NULL, 564, 1, 600.00, NULL, 0, NULL, NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `matriz` VALUES (309, NULL, NULL, 'todo tipo de combustible equipo:testo', NULL, 570, 1, 350.00, NULL, 0, NULL, NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `matriz` VALUES (310, NULL, NULL, 'tipo de combustible: solo gas natural\n            equipo:testo\n            selección de sitio de muestreo:motores reciprocantes,calderos y hornos de proceso', NULL, 567, 1, 350.00, NULL, 0, NULL, NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);

-- ----------------------------
-- Table structure for methodologies
-- ----------------------------
DROP TABLE IF EXISTS `methodologies`;
CREATE TABLE `methodologies`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 571 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of methodologies
-- ----------------------------
INSERT INTO `methodologies` VALUES (373, 'GESTION AMBIENTAL. Calidad de aire. Método de referencia para la determinación de material particulado respirable como PM10 en la atmósfera                                                                                                                           NTP 900.030 (2018)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (374, 'Method for the determination of fine particulate Matter as PM2.5 in the atmosphere                                                                                                       EPA CFR 40, Part 50 Appendix L (2018)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (375, 'Sampling of Ambient Air for PM10 Concentration Using the Rupprecht and Patashnick (R&P). Low Volume Partisol Sampler.  EPA Compendium, Method IO-2.3 (1999)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (376, 'Reference method for the determination of particulate matter as PM10 in the atmosphere. EPA CFR 40, Appendix J to part 50 (validado aplicado fuera del alcance) (2018)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (377, 'EPA Compendium Method IO-3.4, June 1999 (Validated – Applied out of scope).\nDetermination Of Metals In Ambient Particulate Matter Using Inductively Coupled Plasma (ICP) Spectroscopy', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (378, 'EPA Compendium Method IO-3.4, June 1999 (Validated Method out of scope).\nDetermination Of Metals In Ambient Particulate Matter Using Inductively Coupled Plasma (ICP) Spectroscopy', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (379, 'EPA Compendium METHOD IO-3.1 // EPA Compedium METHOD IO-3.4-(1999)\nDetermination of Metals and Trace Elements in Water and Wastes by Inductively Coupled Plasma-Atomic Emission Spectrometry', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (380, 'EPA-454/B-08-002 2024 (Validated Method out of scope)\nQuality Assurance Handbook for Air Pollution Measurement Systems. Volume IV: Meteorological Measurements Version 2.0 (Final)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (381, 'Reference Method for the Determination of Sulfur Dioxide in the Atmosphere (Pararosaniline Method)  EPA CFR 40 Appendix A-2 to Part 50 (2018)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (382, 'Standard Test Method for Nitrogen Dioxide Content of the Atmosphere (Griess-Saltzman Reaction) ASTM D1607 - 91 (2018)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (383, 'Determinación de monóxido de carbono en la atmósfera                                                 GREENLAB-006 (Basado en EPA Method 10A) (validado) (2018)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (384, 'Determinación de la concentración de sulfuro de hidrógeno (H2S) en la atmósfera  James P. Lodge Jr. – Methods of Air Sampling and Analysis, Third Edition, Part 701. 1980 (Validado-Modificado) (2018)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (385, 'Método de determinación de ozono en la atmósfera                                      GREENLAB-001.(Basado en EPA CFR 40. Appendix J to part 50)(validado) (2018)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (386, 'Referenced in Peter O.  Warner. Analysis of Air \nPollutants, Spanish Ed 1981. Cap. 3, pp. 147-151. \n(Validated Method out of scope).\nDetermination of Nitrogen Oxides in Air Quality NOx (NO2 + NO)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (387, 'Calidad del aire ambiente. Método normalizado de medida de las concentraciones de benceno. Parte 2: Muestreo por aspiración seguido de desorción por disolvente y cromatografía de gases                                                                     UNE-EN 14662-2 (2006)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (388, 'PNTE-LTE-04 Rev.1 (Basado en NIOSH Manual of Analytical Methods, 3rd. ed., Method 6000). (Validado). 2018', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (389, 'NTP 900.036:2017 \nENVIRONMENTAL QUALITY MONITORING. Air quality. Passive diffusion samplers for the determination of gas and vapour concentration. Requirements and test methods. Part 1: General requirements. 2nd Edition', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (390, 'Standard Test Method for Analysis of Organic Compound Vapors Collected by the Activated Charcoal Tube Adsorption Method                                                                                                                          ASTM D3687 - 19 (2019)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (391, 'Aire ambiental. Determinación de dióxido de azufre. Método de Fluorescencia ultravioleta CORRIGENDA 1 1ª Edición NTP-ISO 10498 2017/COR 1:2017 (2017)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (392, 'Aire ambiental. Determinación de la concentración másica de óxidos de nitrógeno. Método de quimioluminiscencia  NTP-ISO 7996 (2019)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (393, 'Aire ambiental. Determinación de monóxido de carbono. Método de espectrometría infrarroja no dispersiva  NTP-ISO 4224 (2019)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (394, 'Determinación de sulfuro de hidrogeno por el Método de Fluorescencia ultravioleta   NTP-ISO 10498: 2017 (VALIDADO-modificado) (2020)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (395, 'NTP-ISO 13964:2020\nAir quality — Determination of ozone in ambient air — Ultraviolet photometric method', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (396, 'NTP-ISO 7996:2019\nAmbient air. Determination of the mass concentration of oxides of nitrogen.', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (397, 'NTP-ISO 7996:2019\nAmbient air. Determination of  the mass concentration of \noxides of nitrogen', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (398, 'NTP 900.068 / 2016-12-31, COR 1:2017\nEnvironmental Quality / Air Quality Monitoring / Standard Method for the Determination of Total Gaseous Mercury', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (399, 'Conductividad. Laboratory Method                                             \nSMEWW-APHA-AWWA-WEF Part 2510 B, 24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (400, 'Membrane-Electrode Method                                                               SMEWW-APHA-AWWA-WEF Part 4500-O G, 24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (401, 'pH Value. Electrometric Method                                                                  SMEWW-APHA-AWWA-WEF Part 4500-H+ B, 24th Ed. (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (402, 'Temperature. Laboratory and Field Methods                                       SMEWW-APHA-AWWA-WEF Part 2550 B, 24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (403, 'Turbidity. Nephelometric Method                                                               SMEWW-APHA-AWWA-WEF Part 2130 B, 24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (404, 'ISO 21413:2005\nManual methods for the measurement of a groundwater level in a well', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (405, 'SMEWW-APHA-AWWA-WEF Part 2520 B. 24th. Ed. 2023\nSalinity. Electrical Conductivity Method', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (406, 'Standard UNE-EN-ISO 748: 2023\nFlow measurement of liquids in open channels using flow meters or floats', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (407, 'ISO 12242:2012\nMeasurement of fluid flow in closed conduits — Ultrasonic transit-time meters for liquid.', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (408, 'NCh 3205-2011 (Validated Method out of scope). Wastewater Flow Meters - Requirement - First Edition', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (409, 'SMEWW-APHA-AWWA-WEF Part 2580 B 24th Ed.2023\nOxidation-Reduction Potential Measurement in Clear Water.', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (410, 'NMX-AA-006-SCFI2010\n(Validated Method out of scope).\nWater Analysis -Determination of floatable Material in Wastewaters and\nTreated Wastewaters -Test Method', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (411, 'SMEWW-APHA-AWWA-WEF Part 4500-Cl G, 24th Ed. 2023\n(Validated Method out of scope).\nChlorine (Residual). DPD Colorimetric Method.', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (412, 'SMEWW-APHA-AWWA-WEF Part -2150 B, 24th.Ed. 2023\nThreshold Odor Test', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (413, 'SMEWW-APHA-AWWA-WEF Part -2160 B, 24th.Ed. 2023\nFlavor Threshold Test (FTT)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (414, 'Oil and Grease. Liquid-Liquid, Partition-Gravimetric Method\n	SMEWW-APHA-AWWA-WEF Part 5520 B,24th Ed.', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (415, 'Acidity. Titration Method                                                                                           SMEWW-APHA-AWWA-WEF Part 2310 B, 24th Ed. (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (416, 'Alkalinity. Titration Method                                                                          SMEWW-APHA-AWWA-WEF Part 2320 B, 24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (417, 'Surfactants. Anionic Surfactants as \nMBAS\nSMEWW-APHA-AWWA-WEF\nPart 5540 C, 24th Ed. 2023', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (418, 'Cyanide. Total Cyanide after Distillation. Cyanide-\nSelective Electrode Method                                                                                                            SMEWW-APHA-AWWA-WEF Part 4500-CN¯ C,F, 24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (419, 'Free Cyanide in Water, Soils and solid wastes by microdiffusion                                                                                                     EPA METHOD 9016 Rev. 0. (2010)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (420, 'Cyanide. Cyanide-Selective Electrode Method.\nWeak Acid Dissociable Cyanide                                                                                                              SMEWW-APHA-AWWA-WEF Part 4500-CN¯ I,F, 24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (421, 'Mercuric Nitrate Method                                                                                      SMEWW-APHA-AWWA-WEF Part 4500-Cl¯ C, 24th Ed. (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (422, 'Chromium. Colorimetric Method                                                                    SMEWW-APHA-AWWA-WEF Part 3500-Cr B , 24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (423, 'Calcium. EDTA Titrimetric Method                                                                                                      SMEWW-APHA-AWWA-WEF Part 3500- Ca-B, 24th Ed. (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (424, 'Hardness. EDTA Titrimetric Method                                                                                                                       SMEWW-APHA-AWWA-WEF Part 2340 C, 24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (425, 'Análisis de Aguas - Determinación de fluroruros en aguas naturales, residuales y residuales tratadas                                                                        NMX-AA-077-SCFI-2001 (2001)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (426, 'Fósforo. Método del ácido ascórbico                                                                SMEWW-APHA-AWWA-WEF Part 4500-P E, 24th rd Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (427, 'Nitrogen (Nitrate). Cadmium Reduction Method                                             SMEWW-APHA-AWWA-WEF Part 4500-NO3¯ E, 24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (428, 'Nitrogen (Nitrite). Colorimetric Method                                                              SMEWW-APHA-AWWA-WEF Part 4500-NO2¯ B, 24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (429, 'Sulfate. Turbidimetric Method                                                                                 SMEWW-APHA-AWWA-WEF Part 4500-SO4= E, 24th Ed. (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (430, 'Nitrogen (Ammonia). Ammonia-Selective\nElectrode Method                                                                          SMEWW-APHA-AWWAWEF Part 4500-NH3 D, 24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (431, 'SMEWW-APHAAWWA-WEF Part \n4500-N C, 24th. Ed. 2023\nDetermination of Total NitrogenPersulfate method', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (432, 'SMEWW-APHA-AWWA-WEF Part 5220 D, 24th Ed	2023	Chemical Oxygen Demand (COD). Closed Reflux, Colorimetric Method', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (433, 'SMEWW-APHA-AWWA-WEF\nPart 5210 B, 24th Ed. Biochemical Oxygen Demand\n(BOD). 5-Day BOD Test', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (434, 'SMEWW-APHAAWWA-WEF\nPart 5220 B 24th Ed. 2023 (Validated Method out of\nscope). Chemical Oxygen Demand.', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (435, 'Phenolics (Spectrophotometric, manual 4-AAP with distillation)                                                                                                      EPA 9065 (1986)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (436, 'Phosphorus. Ascorbic Acid Method                                                                 SMEWW-APHA-AWWA-WEF Part 4500-P, B (ítem 5) y E,\n24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (437, 'Determination of inorganic anions\nby ion chromatography', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (438, 'Solids. Settleable Solids                                                                         SMEWW-APHA-AWWA-WEF Part 2540 F, 24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (439, 'Solids. Total Solids Dried at 103-105°C                                                                SMEWW-APHA-AWWA-WEF Part 2540 B, 24th Ed. (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (440, 'Solids. Total Disolved Solids Dried at 180°C                                                SMEWW-APHA-AWWA-WEF Part 2540 C, 24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (441, 'Solids. Total Suspended Solids Dried at 103-105°C                                             SMEWW-APHA-AWWA-WEF Part 2540 D, 24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (442, '4500-S¯2 C. Sample Pretreatment to Remove Interfering Substances or to Concentrate the Sulfide // 4500-S2¯D. Sulfide. Methylene Blue Method                                                                                  SMEWW APHA-AWWA-WEF Part 4500-S2- C. Pág. 4-175\n// SMEWW-APHA-AWWAWEF Part 4500-S2¯ D Pág. 4-175, 24th Ed (2023)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (443, 'Nonhalogenated Organics by Gas Chromatography                                  EPA METHOD 8015C Rev. 03 2007 (2007)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (444, 'Method for the Determination of Extractable Petroleum\nHydrocarbons (EPH) (Validated) EPH Method Massachusetts\nDepartment Of Environmental Protection May 2004 Revision 1.1\n(Validated)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (445, 'Determination of Metals and Trace Elements in Water and Wastes by Inductively Coupled Plasma-Atomic Emission Spectrometry                                                                                                         EPA Method 200.7, Revisión 4.4. (1994)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (446, 'Determination of Metals and Trace Elements in Water and Wastes by Inductively Coupled Plasma-Atomic Emission Spectrometry     EPA Method 200.7, Revisión 4.4 (1994) (VALIDADO - Aplicado fuera del alcance). (2020 )', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (447, 'Determination of Metals and Trace Elements in Water and Wastes by Inductively Coupled Plasma-Atomic Emission Spectrometry     EPA Method 200.7, Revisión 4.4 (1994) (VALIDADO - Aplicado fuera del alcance). (2020)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (448, 'Determination of Metals and Trace Elements in Water and Wastes by Inductively Coupled Plasma-Atomic Emission Spectrometry                                EPA Method 200.7, Revisión 4.4 (1994) (VALIDADO - Aplicado fuera del alcance). (2022)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (449, 'Determination of Metals and Trace Elements in Water and Wastes by Inductively Coupled Plasma-Atomic Emission Spectrometry.    EPA Method 200.7, Revisión 4.4 (1994)                                                                                    (VALIDADO - Aplicado fuera del alcance). (2022)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (450, 'Color. Spectrophotometric - Single \n- Wavelength Method (Proposed)\nSMEWW-APHA-AWWA-WEF\nPart 2120 C, 24th Ed. 2023', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (451, 'SMEWW-APHA-AWWA-WEF Part 4500-NH3 D, 23 er Ed', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (452, 'Volatile Organic Compounds By GasChromatography/Mass Spectrometry(GC/MS). // Volatile Organic Compounds in Various Sample Matrices using Equilibrium Headspace AnalysisEPA Method 8260B Revision 2 December 1996. // EPA Method 5021A Revision 2 July 2014(Cromatografía de Gases con detector de Masas (GC-MS) en modo SIM (Monitoreo de Iones Seleccionados))', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (453, 'Volatile Organic Compounds by GasChromatography/Mass Spectrometry (GC/MS). // Volatile Organic Compounds in Various SampleMatrices using Equilibrium Headspace Analysis.EPA Method 8260B Revision 2 December 1996. // EPA Method 5021A Revision 2 July 2014(Cromatografía de Gases con detector de Masas (GC-MS) en modo SIM (Monitoreo de Iones Seleccionados))', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (454, 'Volatile Organic Compounds By Gas Chromatography/Mass Spectrometry(GC/MS). // Volatile Organic Compounds in Various Sample Matrices using Equilibrium Headspace Analysis.EPA Method 8260B Rev 2 December 1996. //EPA Method 5021A Rev 2 July 2014(Cromatografía de Gases con detector de Masas (GC-MS) en modo SIM (Monitoreo de Iones Seleccionados))', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (455, 'Semi-volatile Organic Compounds by Gas Chromatography/Mass Spectrometry Separatory funnel liquid-liquid extraction EPA Method 8270D Rev 5 July 2014 // EPAMethod 3510C Revision 3 December 1996(Cromatografía de Gases con detector de Masas (GC-MS) en modo SIM (Monitoreo de Iones Seleccionados))', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (456, 'Semi-volatile Organic Compounds by Gas Chromatography/Mass Spectrometry // Separatory funnel liquid-liquid extractionEPA Method 8270D Rev 5 July 2014 // EPAMethod 3510C Rev 3 December 1996(Cromatografía de Gases con detector de Masas (GC-MS) en modo SIM (Monitoreo de Iones Seleccionados))', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (457, 'Semi-volatile Organic Compounds 150.00 8 1,200.00\nby Gas Chromatography/Mass\nSpectrometry\nSeparatory funnel liquid-liquid\nextraction\nEPA Method 8270E Rev 6 Jun\n2018 // EPA Method 3510C\nRevision 3 December 1996', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (458, 'Organochlorine Pesticides by Gas \nChromatography // Separatory\nfunnel liquid-liquid extraction\nEPA Method 8081B Revision 2\nFebruary 2007 // EPA Method\n3510C Revision 3 December 1996', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (459, 'EPA Method 8270E\nRev 6 Jun 2018 //\nEPA Method 3510C \nRevision 3 December \n1996\nSemi-volatile Organic \nCompounds by Gas \nChromatography/Mas\ns Spectrometry\nSeparatory funnel \nliquid-liquid extraction', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (460, 'EPA Method 8270E\nRev 6 Jun 2018 //\nEPA Method 3510C \nRev 3 December \n1996\nSemi-volatile Organic \nCompounds by Gas \nChromatography/Mas\ns Spectrometry\n// Separatory funnel \nliquid-liquid extraction', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (461, 'Semi-volatile Organic Compounds 500.00 8 4,000.00\nby Gas Chromatography/Mass\nSpectrometry\nSeparatory funnel liquid-liquid\nextraction\nEPA Method 8270E Rev 6 Jun\n2018 // EPA Method 3510C\nRevision 3 December 1996\n(Validate)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (462, 'ISO 20179:2005', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (463, 'Polychlorinated Biphenyls (PCBs) by Gas Chromatography //\nSeparatory funnel liquid-liquid extraction\nEPA Method 8082A Revision 1\nFebruary 2007 // EPA Method\n3510C Revision 3 December 1996', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (464, 'Thermotolerant (Fecal) Coliform Procedure\nSM 9221E/9221C. Standard\nMethods. 24th.Ed. 2023', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (465, 'Standard Total Coliform Fermentation Technique\nSM 9221B/ 9221C. Standard\nMethods. 24th.Ed. 2023', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (466, 'Escherichia coli Procedure using\nFluorogenic Substrate\nSM 9221 F/ 9221C. Standard\nMethods. 24th.Ed. 2023', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (467, 'Analysis of \nWastewater for Use \nin Agriculture - A \nLaboratory Manual of \nParasitological and \nBacteriological \nTechniques. Rachel \nM. Ayres & D. \nDuncan Mara. World \nHealth Organization. \n1996\nModified Bailenger \nMethod (Validated)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (468, 'Somatic Coliphages Assay \nSMEWW 9224 B, 24th.Ed. 2023', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (469, 'Plankton. Concentration Techniques. Phytoplankton\nCounting Techniques / Plankton. Zooplankton. Counting\nTechniques.\nSMEWW-APHA-AWWA-WEF\nPart 10200 C.1.2, F.2. a, c.1, 24th.Ed. 2023 /\nSMEWW-APHAAWWA-WEF Part\n10200 G, 24th.Ed. 2023', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (470, 'Analysis of \nWastewater for \nUse in \nAgriculture - A \nLaboratory \nManual of \nParasitological \nand \nBacteriological \nTechniques. \nRachel M. \nAyres & D. \nDuncan Mara. \nWorld Health \nOrganization. \n1996\nModified Bailenger \nMethod (Validated)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (471, 'Heterotrophic Plate Count Pour Plate Method.\nSM 9215A/ 9215B. Standard\nMethods 24th.Ed. 2023', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (472, 'Analysis of Wastewater for Use in \nAgriculture - A Laboratory Manual\nof Parasitological and\nBacteriological Techniques. Rachel\nM. Ayres & D. Duncan Mara.\nWorld Health Organization. 1996.\nModified Bailenger Method\nValidated', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (473, 'Analysis of Wastewater for Use in\nAgriculture - A Laboratory Manual\nof Parasitological and\nBacteriological Techniques. Rachel\nM. Ayres & D. Duncan Mara.\nWorld Health Organization. 1996.\nModified Bailenger Method\nValidated', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (474, 'Detection of Pathogenic Bacteria.\nVibrio.SM 9278 B. Standard Methods\n24th.Ed. 2023', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (475, 'SMEWW-APHAAWWA-WEF Part \n9230 B, 24th Ed, \n2023 Fecal enterococcus/ \nStreptococcus  Groups by MultipleTube Technique', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (476, 'SM 9213 F. Standard Methods 24th.Ed. \n2023 Multiple-Tube  Technique for  Pseudomonas \naeruginosa', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (477, 'SM 9274 B Standard  Methods 24th.Ed. 2023\nDetection of  Pathogenic Bacteria. \nSalmonella sp.', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (478, 'SM 9213 B. Standard  Methods 24th.Ed. 2023\nSwimming Pools. 6.Test for \nStaphylococcus  aureus', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (479, 'Plankton. Phytoplankton Counting \nTechniques\nSMEWW-APHA-AWWA-WEF\nPart 10200 F, items: F.2.a, F.2.b\nand F.2.c.1, 24th.Ed. 2023', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (480, 'Plankton. Zooplankton Counting \nTechniques\nSMEWW-APHA-AWWA-WEF\nPart 10200 G. 24th.Ed. 2023', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (481, 'SMEWW-APHA-AWWA WEF Part 10500 C. 24th\nEd. 2023', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (482, 'Alkaline Digestion for Hexavalent Chromium\n// Chromium, Hexavalent (Colorimetric). EPA Method 3060A Rev.01:1996 // EPA Method 7196A Rev.01:1992', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (483, 'Cyanide extraction procedure for solids and oils // Cyanide. Cyanide-Selective Electrode Method.  EPA Method 9013A-Rev.02:2014 // SMEWW-APHA-AWWA-WEF Part 4500-CN¯ F, 23rd Ed. 2017', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (484, 'Acid digestion of sediments, sludges, and soils //\nDetermination of metals and trace elements in water and wastes by inductively coupled plasma-atomic emission spectrometry.   EPA METHOD 3050 B, Rev. 2 //  EPA METHOD 200.7, REV. 4.4 (1996 // 1994)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (485, 'Nonhalogenated Organics By Gas Chromatography.\nEPA Method 8015C Revision 3, 2007', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (486, 'Volatile Organic Compounds by \nGas Chromatography/ Mass\nSpectrometry (GC/MS). // Volatile\nOrganic Compounds in Various\nSample Matrices using Equilibrium\nHeadspace Analysis.\nEPA Method 8260D Rev 4 June\n2018. // EPA Method 5021A Rev 2\nJuly 2014', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (487, 'Semi-volatile Organic Compounds\nby Gas Chromatography/Mass\nSpectrometry // Ultrasonic\nExtraction.\nEPA Method 8270E Rev 6 Jun\n2018 // EPA Method 3550C Rev 3\nFebruary 2007', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (488, 'Semi-volatile Organic Compounds \nby Gas Chromatography/Mass\nSpectrometry // Ultrasonic\nExtraction.\nEPA Method 8270E Rev 6 Jun\n2018 // EPA Method 3550C Rev 3\nFebruary 2007', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (489, 'EPA Method 8260D Rev 4 June 2018. // EPA Method 5021A Rev 2 July 2014\nVolatile Organic Compounds by Gas Chromatography/ Mass Spectrometry (GC/MS). // Volatile Organic Compounds in Various Sample Matrices using Equilibrium Headspace Analysis', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (490, 'Nonhalogenated Organics by Gas Chromatography\nEPA Method 8015 C, Revisión 3', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (491, 'EPA Method 8270E Rev 6 Jun 2018. // EPA Method 3550C Revision 3 February 2007\nSemi-volatile Organic Compounds by Gas Chromatography/Mass Spectrometry // Ultrasonic Extraction', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (492, 'EPA Method 8270E Rev 6 Jun 2018 // EPA Method 3550C Rev 3 February 2007\nSemi-volatile Organic Compounds by Gas Chromatography/Mass Spectrometry // Ultrasonic Extraction', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (493, 'EPA Method 8270E Rev 6 Jun 2018. // EPA Method 3550C Rev 3 February 2007\nSemi-volatile Organic Compounds by Gas Chromatography/Mass Spectrometry // Ultrasonic Extraction', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (494, 'Acid digestion of sediments, sludges, and soils //\nDetermination of metals and trace elements in water and wastes by inductively coupled plasma-atomic emission spectrometry.                                                                                             EPA METHOD 3050 B, Rev. 2 //  EPA METHOD 200.7, REV. 4.4 (1996 // 1994)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (495, 'Acústica. Descripción, medición y evaluación del ruido ambiental. Parte1: Índices básicos y procedimiento de evaluación // Acústica. Descripción, medición y evaluación del ruido ambiental. Parte 2: Determinación de los niveles de presión sonora.\nNTP ISO 1996-1:2020 // NTP-ISO 1996-2:2023', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (496, 'DIN 4150-3; DIN 4150-2; DIN 4150-1 Part 3: Effects on structures, Part 2: Effects on persons in buildings, Part 1: Prediction of\nvibration parameters.', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (497, 'NTP-ISO 2631-1:2011 (revised 2022) Mechanical vibration and shock. Evaluation of human exposure to whole-body vibration. Part 1: General requirements', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (498, 'IEEE 644-2019. IEEE Standard Procedures for Measurement of Power Frequency Electric and Magnetic Fields from AC Power Lines.', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (499, 'UNE-EN 62110:2013\nElectric and magnetic field levels generated by alternate power systems. Measurement procedures with regard to public exposure', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (500, 'Particulates not otherwise regulated. Total.                                            NIOSH 0500. Isuue 2 (1994)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (501, 'Particulates not otherwise regulated. Respirable.                                        NIOSH 0600. Isuue 3 (1998)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (502, 'NIOSH 7301, Issue 1. ELEMENTS by ICP (Aqua Regia Ashing), 2003.', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (503, 'MERCURY: METHOD 6009, Issue 2 / Inductively Coupled Plasma-Optical Emission Spectro (Validated Method out of scope).', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (504, 'Particulates not otherwise regulated. Total.                                             NIOSH 0500, Issue 3 (validado modificado). No incluye muestreo. (2018)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (505, 'Particulates not otherwise regulated.Respirable.                                      NIOSH 0600.Issue 3 (validado modificado).No incluye muestreo. (2018)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (506, 'UNE-EN 12464-1:2022. Lighting of workplaces. Part 1: Workplaces indoors.\nUNE-EN 12464-2:2016. Lighting of workplaces. Part 2: External workplaces', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (507, 'NTP-ISO 9612:2010 (revised 2020)\nAcoustics. Determination of occupational noise exposure. Engineering method.', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (508, 'UNE – EN ISO 5349-2:2002/A1:2016\nEN ISO 5349-2:2001/A1 Mechanical vibration -\nMeasurement and evaluation of human exposure to hand transmitted vibration - Part 2: Practical guidance for measurement at the workplace', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (509, 'Determination of Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas-Fired Engines, Boilers and Process Heaters Using Portable Analyzers  CTM 034 (1997)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (510, 'Determination of Sulfur Dioxide Emissions from Stationary Sources (Instrumental analyzer procedure)                                                                  EPA 40 CFR, Appendix A-4 to Part 60. Method 6C (2017) (Validado-modificado) (2022)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (511, 'EPA 40 CFR Appendix A-4 to Part 60, Method 10. 2023\nDetermination of Carbon Monoxide Emissions from Stationary Sources (Instrumental Analyzer Procedure)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (512, 'EPA Method AP-42: Compilation of Air\nEmissions Factors (2009.)\nCompilation of Air Emissions Factors\n(Determination of Particulate Matter (PM))', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (513, 'NTP 712.110:2022\nMONITOREO DE EMISIONES ATMOSFÉRICAS. \nDetermination of Metals Emissions from Stationary Sources. 1ª Edition\nEPA Method 29 (7-1-23)\nDetermination of Metals Emissions from Stationary Sources - by Inductively Coupled Plasma-Atomic Emission Spectrometry', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (514, 'NTP 712.110:2022\n (Validated Method out of scope)\nMONITOREO DE EMISIONES ATMOSFÉRICAS. Determination of Metals Emissions from Stationary Sources. 1ª Edition\nEPA Method 29 (7-1-23)\n (Validated Method out of scope)\nDetermination of Metals Emissions from Stationary Sources - by Inductively Coupled Plasma-Atomic Emission Spectrometry', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (515, 'EPA Method 16A\n(7-1-23)\nDetermination of Total Reduced Sulfur Emissions From Stationary Sources (Impinger Technique)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (516, 'EPA Method 6 (7-1-23)\nDetermination of Sulfur Dioxide emissions from stationary sources\nNTP 900.006:2021\nATMOSPHERIC EMISSIONS MONITORING.\nDetermination of Sulfur Dioxide emissions from stationary sources.', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (517, 'EPA Method 8 (7-1-23) Determination of Sulfuric Acid and Sulfur Dioxide Emissions from stationary sources', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (518, 'EPA-40 CFR, Part 60, Appendix A-7, Method 7:2023 Determination of nitrogen oxide emissions from stationary sources\nNTP 900.007: 2021 Determination of nitrogen oxide emissions from stationary sources', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (519, 'NTP 712.120:2022 Monitoring of atmospheric emissions.\nDetermination of nitrogen oxide emissions in stationary sources. Instrumental analyzer procedure.\nEPA 40 CFR Appendix A-5 to Part 60, Method 7E: 2023\nDetermination of nitrogen oxides emissions from stationary sources (instrumental analyzer procedure)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (520, 'EPA Method 18 (7-1-23)\n Measurement of Gaseous Organic Compound Emissions by Gas Chromatography\nNTP 900.018:2021 \nATMOSPHERIC EMISSIONS MONITORING.Measurement of Gaseous Organic Compound Emissions by Gas Chromatography', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (521, 'NTP 900.001:2021 Monitoring Of Atmospheric Emissions.\nDetermination of transverse sampling points for velocity\nmeasurement in stationary sources. 2nd Edition, 2021\nMethod 1 (7-1-23)\nSample and Velocity Traverses for Stationary Sources Flow EPA 40 CFR Appendix A-1 to Part 60 Method 2: 2023, Determination of Stack Gas Velocity and Volumetric Flow Rate (Type S Pitot Tube)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (522, 'NTP 712.112:2022\nMonitoring of atmospheric emissions. Determination of gas velocity and volumetric flow rate in chimneys or small ducts (Standard Pitot Tube). 1a Edition', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (523, 'EPA 40 CFR Appendix A-1 to Part 60 Method 2C: 2023\nDetermination of gas velocity and volumetric flow rate in small stacks or ducts (standard pitot tube)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (524, 'NTP 900.004:2021 Monitoring\nof Atmospheric Emissions. Determination of moisture content in chimney gases. 2nd\nEdition /2021 METHOD 4 (7-1-23) Determination of Moisture content in stack gases', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (525, 'ASTM D2156-09(2018)\nStandard Test Method for Smoke Density in Flue Gases from Burning Distillate Fuels', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (526, 'EPA Method 3 A (7-1-23)\nDetermination of Oxygen and Carbon Dioxide Concentrations in Emissions from Stationary Sources (Instrumental Analyzer Procedure)\nNTP 712.111:2021\nMONITORING OF ATMOSPHERIC EMISSIONS.\nDetermination of Oxygen and Carbon Dioxide Concentrations in Emissions from Stationary Sources (Instrumental Analyzer Procedure)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (527, 'METHOD 3 (7-1-23)\n GAS ANALYSIS FOR THE DETERMINATION OF DRY MOLECULAR WEIGHT', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (528, 'EPA 40 CFR Appendix A-4 to Part 60, Method 10. 2023\nDetermination of Carbon Monoxide Emissions from Stationary Sources (Instrumental Analyzer Procedure)\nNTP 900.010:2021\nATMOSPHERIC EMISSIONS MONITORING.\nDetermination of Carbon Monoxide Emissions from Stationary Sources (Instrumental Analyzer Procedure)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (529, 'EPA CTM-022\n Determination of Nitric Oxide, Nitrogen Dioxide\nand NOx Emissions from Stationary Combustion Sources by electrochemical analyzer. 1995', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (530, 'EPA CTM-030\n Determination of Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas-Fired Engines, Boilers and Process Heaters Using Portable Analyzers. 1997', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (531, 'CTM 022\n (Validated Method out of scope)\nNitric Oxide, Nitrogen Dioxide, & NOx emissions by Electrochemical Analyzer', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (532, 'CTM 030\n (Validated Method out of scope)\nDetermination of Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas-Fired Engines, Boilers and Process Heaters Using Portable Analyzers', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (533, 'CTM-034 \n(Validated Method out of scope)\nTest Method -Determination of Oxygen, Carbon Monoxide and Oxides of Nitrogen For Periodic Monitoring. Determination of\nNitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas Fired Engines, Boilers and Process Heaters Using\nPortable Analyzers', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (534, 'Determination of Particulate Matter Emissions from Stationary Sources                                                                                               EPA CFR 40, Part 60, Appendix A. Method 5 (1999)', NULL, '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `methodologies` VALUES (535, 'Temperature. Laboratory and Field Methods                                       SMEWW-APHA-AWWA-WEF Part 2550 B, 23 er Ed 2017', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (536, 'pH Value. Electrometric Method                                                                  SMEWW-APHA-AWWA-WEF Part 4500-H+ B, 23 er Ed. 2017', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (537, 'Conductividad. Laboratory Method.                                                    SMEWW-APHA-AWWA-WEF Part 2510 B, 23 er Ed. 2017', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (538, 'Turbidity. Nephelometric Method                                                               SMEWW-APHA-AWWA-WEF Part 2130 B, 23 er Ed. 2017', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (539, 'Membrane-Electrode Method                                                               SMEWW-APHA-AWWA-WEF Part 4500-O G, 23 er Ed. 2017', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (540, 'Solids. Total Suspended Solids Dried at 103-105°C                                                SMEWW-APHA-AWWA-WEF Part 2540 C, 23 er Ed. 2017', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (541, 'Solids. Total Suspended Solids Dried at 103-105°C                                             SMEWW-APHA-AWWA-WEF Part 2540 D,23 er Ed.2017', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (542, 'Color. Spectrophotometric-Single-Wavelength Method (Proposed)\nSMEWW-APHA-AWWA-WEF Part 2120-C, 23rd. Ed. 2017', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (543, 'AEN-PO-010, Basado en SMEWW-APHA-AWWA-WEF Part 4500-Cl G, 23 rd Ed.(Validado) 2017', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (544, 'Mercuric Nitrate Method                                                                                      SMEWW-APHA-AWWA-WEF Part 4500-Cl¯ C, 23 er Ed. 2017', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (545, 'Sulfate. Turbidimetric Method                                                                                 SMEWW-APHA-AWWA-WEF Part 4500-SO4= E, 23 er Ed. 2017', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (546, 'Nitrogen (Ammonia). Ammonia-Selective Electrode Method                   SMEWW-APHA-AWWA-WEF Part 4500-NH3 D, 23 er Ed 2017', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (547, 'Determination of Metals and Trace Elements in Water and Wastes by Inductively Coupled Plasma-Atomic Emission Spectrometry                                                                                                         EPA Method 200.7, Revisión 4.4. 1994', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (548, 'SMEWW-APHA-AWWA-WEF. Part 2120 C. 23rd Ed. 2017. Color. Spectrophotometric Single-Wavelength Method (Proposed). Color', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (549, 'SMEWW-APHA-AWWA-WEF Part 5210 B, 24th Ed.\nBiochemical Oxygen Demand (BOD). 5-Day BOD Test', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (550, 'Chemical Oxygen Demand (COD). Closed Reflux,\nColorimetric Method                                                                                                          SMEWW-APHA-AWWA-WEF Part 5220 D, 24th Ed (2023)', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (551, 'SMEWW-APHA-AWWA-WEF Part 5540 C, 23rd Ed. 2017 Anionic Surfactants as MBA', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (552, 'EPA 300.0 Rev. 2.1, 1993,\nVALIDATED (Applied out of reach), 2019\nDetermination of inorganic anions by ion chromatography', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (553, 'Polychlorinated Biphenyls (PCBs) by Gas Chromatography Separatory funnel liquid-liquid extraction.EPA Method 8082A Revision 1 February 2007 // EPA Method 3510C Revision 3 December 1996(Cromatografía de Gases con detector de captura de electrones (ECD))', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (554, 'EPA Method 8270E Rev 6 Jun 2018 // EPA Method 3510C Revision 3 December 1996\nSemi-volatile Organic Compounds by Gas Chromatography/Mass Spectrometry // Separatory funnel liquid-liquid extraction', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (555, 'EPA Method 8270E Rev 6 Jun 2018 // EPA Method 3510C Revision 3 December 1996\nSemi-volatile Organic Compounds by Gas Chromatography/Mass Spectrometry //\nSeparatory funnel liquid-liquid extraction (Validated)', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (556, 'Enumeration of Fecal Coliforms by MPN method Fecal Coliform ProcedureSM 9221 E. / 9221C. Standard Methods 23rd Edition 2017', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (557, 'SM 9221 F. / 9221C. Standard Methods 24th.Ed. 2023\nEscherichia coli Procedure using Fluorogenic Substrate', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (558, 'Analysis of Wastewater for Use in Agriculture - A Laboratory Manual of Parasitological and Bacterio-logical Techniques. Rachel M. Ayres & D. Duncan Mara. World Health Organization. 1996 Modified Bailenger Method', NULL, '2026-04-11 12:57:33', '2026-04-11 17:57:33', NULL);
INSERT INTO `methodologies` VALUES (559, 'EPA Compendium Method IO-3.4, June 1999 (Validated – Applied out of scope).\nDetermination Of Metals In Ambient Particulate Matter Using Inductively Coupled Plasma (ICP) Spectroscopy', NULL, '2026-04-11 14:13:59', '2026-04-11 19:13:59', NULL);
INSERT INTO `methodologies` VALUES (560, 'Volatile Organic Compounds by \nGas Chromatography/ Mass\nSpectrometry (GC/MS). // Volatile\nOrganic Compounds in Various\nSample Matrices using Equilibrium\nHeadspace Analysis.\nEPA Method 8260D Rev 4 June\n2018. // EPA Method 5021A Rev 2\nJuly 2014', NULL, '2026-04-11 14:51:40', '2026-04-11 19:51:40', NULL);
INSERT INTO `methodologies` VALUES (561, '', NULL, '2026-04-11 16:19:48', '2026-04-11 21:19:48', NULL);
INSERT INTO `methodologies` VALUES (562, 'EPA Method 8270E Rev 6 Jun 2018. // EPA Method 3550C Rev 3 February 2007\n                    Semi-volatile Organic Compounds by Gas Chromatography/Mass Spectrometry // Ultrasonic Extraction', NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `methodologies` VALUES (563, 'NTP 712.110:2022\n                    (Validated Method out of scope)\n                    MONITOREO DE EMISIONES ATMOSFÉRICAS. Determination of Metals Emissions from Stationary Sources. 1ª Edition\n                    EPA Method 29 (7-1-23)\n                    (Validated Method out of scope)\n                    Determination of Metals Emissions from Stationary Sources - by Inductively Coupled Plasma-Atomic Emission Spectrometry', NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `methodologies` VALUES (564, 'EPA Method 18 (7-1-23)\n                    Measurement of Gaseous Organic Compound Emissions by Gas Chromatography\n                    NTP 900.018:2021 \n                    ATMOSPHERIC EMISSIONS MONITORING.Measurement of Gaseous Organic Compound Emissions by Gas Chromatography', NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `methodologies` VALUES (565, 'METHOD 3 (7-1-23)\n                    GAS ANALYSIS FOR THE DETERMINATION OF DRY MOLECULAR WEIGHT', NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `methodologies` VALUES (566, 'EPA CTM-022\n                        Determination of Nitric Oxide, Nitrogen Dioxide\n                        and NOx Emissions from Stationary Combustion Sources by electrochemical analyzer. 1995', NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `methodologies` VALUES (567, 'EPA CTM-030\n                    Determination of Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas-Fired Engines, Boilers and Process Heaters Using Portable Analyzers. 1997', NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `methodologies` VALUES (568, 'CTM 030\n                    (Validated Method out of scope)\n                    Determination of Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas-Fired Engines, Boilers and Process Heaters Using Portable Analyzers', NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `methodologies` VALUES (569, 'CTM-034 \n                    (Validated Method out of scope)\n                    Test Method -Determination of Oxygen, Carbon Monoxide and Oxides of Nitrogen For Periodic Monitoring. Determination of\n                    Nitrogen Oxides, Carbon Monoxide, and Oxygen Emissions from Natural Gas Fired Engines, Boilers and Process Heaters Using\n                    Portable Analyzers', NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);
INSERT INTO `methodologies` VALUES (570, 'EPA CTM-022\n                    Determination of Nitric Oxide, Nitrogen Dioxide\n                    and NOx Emissions from Stationary Combustion Sources by electrochemical analyzer. 1995', NULL, '2026-04-11 18:06:00', '2026-04-11 23:06:00', NULL);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2026_02_07_145954_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2026_02_15_083120_create_companies_table', 1);
INSERT INTO `migrations` VALUES (3, '2026_02_15_083130_create_contact_companies_table', 1);
INSERT INTO `migrations` VALUES (4, '2026_02_21_055445_create_units_measurement_table', 1);
INSERT INTO `migrations` VALUES (5, '2026_02_21_055505_create_conditions_table', 1);
INSERT INTO `migrations` VALUES (6, '2026_02_21_055529_create_methodologies_table', 1);
INSERT INTO `migrations` VALUES (7, '2026_02_21_055533_create_essays_table', 1);
INSERT INTO `migrations` VALUES (8, '2026_02_21_055537_create_matriz_table', 1);
INSERT INTO `migrations` VALUES (9, '2026_02_21_055603_create_laboratory_analysis_table', 1);
INSERT INTO `migrations` VALUES (10, '2026_02_21_062124_create_items_matriz_table', 1);
INSERT INTO `migrations` VALUES (11, '2026_03_06_011857_create_quotes_table', 1);
INSERT INTO `migrations` VALUES (12, '2026_03_06_015518_create_services_table', 1);
INSERT INTO `migrations` VALUES (13, '2026_03_06_015947_create_items_quotes_table', 1);
INSERT INTO `migrations` VALUES (14, '2026_03_20_044812_create_permission_tables', 1);
INSERT INTO `migrations` VALUES (15, '2026_03_25_215858_create_logistic_cats_table', 1);
INSERT INTO `migrations` VALUES (16, '2026_03_25_222712_create_order_service_table', 1);
INSERT INTO `migrations` VALUES (17, '2026_03_29_072649_create_items_order_service_table', 1);
INSERT INTO `migrations` VALUES (18, '2026_04_04_194214_create_relations_essay_team_table', 1);
INSERT INTO `migrations` VALUES (19, '2026_04_15_032406_create_chain_custody_table', 2);

-- ----------------------------
-- Table structure for model_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `model_has_permissions`;
CREATE TABLE `model_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_permissions_model_id_model_type_index`(`model_id` ASC, `model_type` ASC) USING BTREE,
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for model_has_roles
-- ----------------------------
DROP TABLE IF EXISTS `model_has_roles`;
CREATE TABLE `model_has_roles`  (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`role_id`, `model_id`, `model_type`) USING BTREE,
  INDEX `model_has_roles_model_id_model_type_index`(`model_id` ASC, `model_type` ASC) USING BTREE,
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of model_has_roles
-- ----------------------------
INSERT INTO `model_has_roles` VALUES (1, 'App\\Models\\Tenant\\User', 1);

-- ----------------------------
-- Table structure for order_service
-- ----------------------------
DROP TABLE IF EXISTS `order_service`;
CREATE TABLE `order_service`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `quote_id` bigint UNSIGNED NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `reviwed_id` bigint UNSIGNED NULL DEFAULT NULL,
  `company_id` bigint UNSIGNED NULL DEFAULT NULL,
  `reviwed` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `reference` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `origin` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `project` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `date_monitoring_init` date NULL DEFAULT NULL,
  `date_monitoring_end` date NULL DEFAULT NULL,
  `date_induction` date NULL DEFAULT NULL,
  `date_output` date NULL DEFAULT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `stations_monitoring` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `project_monitoring` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `conditions` json NULL,
  `emision_data` json NULL,
  `observations` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `contact_id` bigint UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_service_quote_id_foreign`(`quote_id` ASC) USING BTREE,
  INDEX `order_service_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `order_service_reviwed_id_foreign`(`reviwed_id` ASC) USING BTREE,
  INDEX `order_service_company_id_foreign`(`company_id` ASC) USING BTREE,
  CONSTRAINT `order_service_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `order_service_quote_id_foreign` FOREIGN KEY (`quote_id`) REFERENCES `quotes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `order_service_reviwed_id_foreign` FOREIGN KEY (`reviwed_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `order_service_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of order_service
-- ----------------------------
INSERT INTO `order_service` VALUES (4, 1, 1, NULL, 1, NULL, 'Callao - Callao - Callao', 'qwdqwd', 'qwd', '2026-04-13', '2026-04-29', NULL, NULL, 'jjjwefwefq', 'feqfqwf', 'qfwfqw', NULL, NULL, 'qwdwqdwdqwd', '2026-04-12 23:48:47', '2026-04-13 04:48:47', NULL, 'OS-0000002', 1);

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `permissions_name_guard_name_unique`(`name` ASC, `guard_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permissions
-- ----------------------------

-- ----------------------------
-- Table structure for quotes
-- ----------------------------
DROP TABLE IF EXISTS `quotes`;
CREATE TABLE `quotes`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `company_id` bigint UNSIGNED NULL DEFAULT NULL,
  `user_id` bigint UNSIGNED NULL DEFAULT NULL,
  `user_validated_id` bigint UNSIGNED NULL DEFAULT NULL,
  `contact_id` bigint UNSIGNED NULL DEFAULT NULL,
  `validated` tinyint(1) NULL DEFAULT NULL,
  `direction` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `date_attention` date NULL DEFAULT NULL,
  `version` int NULL DEFAULT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `items_total` decimal(10, 2) NOT NULL,
  `other_expenses_total` decimal(10, 2) NOT NULL,
  `igv` decimal(10, 2) NOT NULL,
  `subtotal` decimal(10, 2) NOT NULL,
  `total` decimal(10, 2) NOT NULL,
  `reference` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `observations` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `quotes_company_id_foreign`(`company_id` ASC) USING BTREE,
  INDEX `quotes_user_id_foreign`(`user_id` ASC) USING BTREE,
  INDEX `quotes_user_validated_id_foreign`(`user_validated_id` ASC) USING BTREE,
  INDEX `quotes_contact_id_foreign`(`contact_id` ASC) USING BTREE,
  CONSTRAINT `quotes_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `quotes_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `contact_companies` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `quotes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `quotes_user_validated_id_foreign` FOREIGN KEY (`user_validated_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of quotes
-- ----------------------------
INSERT INTO `quotes` VALUES (1, 1, 1, NULL, 1, NULL, 'Av. Peru 4837', '2026-04-10', NULL, NULL, 2010.00, 0.00, 361.80, 2010.00, 2371.80, 'Callao - Callao - Callao', '-', '2026-04-11 18:21:20', '2026-04-11 23:21:20', NULL);

-- ----------------------------
-- Table structure for relations_essay_team
-- ----------------------------
DROP TABLE IF EXISTS `relations_essay_team`;
CREATE TABLE `relations_essay_team`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `essay_id` bigint UNSIGNED NULL DEFAULT NULL,
  `team_id` bigint UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `relations_essay_team_essay_id_foreign`(`essay_id` ASC) USING BTREE,
  CONSTRAINT `relations_essay_team_essay_id_foreign` FOREIGN KEY (`essay_id`) REFERENCES `essays` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of relations_essay_team
-- ----------------------------

-- ----------------------------
-- Table structure for role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions`  (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`, `role_id`) USING BTREE,
  INDEX `role_has_permissions_role_id_foreign`(`role_id` ASC) USING BTREE,
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_has_permissions
-- ----------------------------

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_name_guard_name_unique`(`name` ASC, `guard_name` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'Super Admin', 'api', '2026-04-11 16:23:22', '2026-04-11 16:23:22');
INSERT INTO `roles` VALUES (2, 'Comercial', 'api', '2026-04-11 16:23:22', '2026-04-11 16:23:22');
INSERT INTO `roles` VALUES (3, 'Recepción', 'api', '2026-04-11 16:23:22', '2026-04-11 16:23:22');
INSERT INTO `roles` VALUES (4, 'Profesional', 'api', '2026-04-11 16:23:22', '2026-04-11 16:23:22');

-- ----------------------------
-- Table structure for services
-- ----------------------------
DROP TABLE IF EXISTS `services`;
CREATE TABLE `services`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `days` int NULL DEFAULT NULL,
  `amount` int NULL DEFAULT NULL,
  `unit_price` decimal(10, 2) NULL DEFAULT NULL,
  `price` decimal(10, 2) NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of services
-- ----------------------------

-- ----------------------------
-- Table structure for units_measurement
-- ----------------------------
DROP TABLE IF EXISTS `units_measurement`;
CREATE TABLE `units_measurement`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 118 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of units_measurement
-- ----------------------------
INSERT INTO `units_measurement` VALUES (79, 'μg/m3', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (80, 'ug/m3', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (81, '°C', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (82, '%', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (83, 'mbar', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (84, 'm/s', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (85, 'º', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (86, 'mm', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (87, 'mg/m3', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (88, 'μS/cm', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (89, 'mg/L', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (90, 'Und. pH', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (91, 'NTU', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (92, '0,1 (z)', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (93, 'm³/s', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (94, 'mV', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (95, 'Presencia/ausencia', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (96, 'mg MBAS/L', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (97, 'mg O₂/L', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (98, 'UC', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (99, 'NMP/100mL', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (100, 'Organismo/L', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (101, 'UFP/L', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (102, 'UFC/mL', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (103, 'Presencia/100mL', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (104, 'Celulas/L', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (105, 'Org./ Área m2', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (106, 'mg/Kg', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (107, 'mg/Kg PS', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (108, 'dB', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (109, 'm/s2', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (110, '(V/m)                   (A/m)                       (W/m2)                  (uT)', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (111, 'mg/filtro', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (112, 'Lux', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (113, 'mg/Nm3', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (114, '3.5 UC', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (115, 'Org/L', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (116, '(UFC/100mL)', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);
INSERT INTO `units_measurement` VALUES (117, 'Huevos/L', '2026-04-11 12:57:32', '2026-04-11 17:57:32', NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `last_name_first` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `last_name_second` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `type_document` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `document_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sex` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `age` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `birthdate` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_professional` tinyint(1) NOT NULL DEFAULT 0,
  `signature` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'SUPER', 'ADMIN', 'ADMIN', 'SUPER ADMIN', 'DNI', '00000000', 'MASCULINO', NULL, NULL, 'super@greenlab.com', '$2y$12$DcEtmn2yr48iymuztYGuWOgUy8u6dfKtw/lQXQFOanh3SJmozxVNq', 0, NULL, 1, '2026-04-11 11:23:22', '2026-04-11 16:23:22', NULL);
INSERT INTO `users` VALUES (2, NULL, NULL, NULL, 'Krystofer Muguerza', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, '2026-04-11 18:17:16', '2026-04-11 23:17:16', NULL);

SET FOREIGN_KEY_CHECKS = 1;
