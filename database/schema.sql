
CREATE DATABASE IF NOT EXISTS trustguard
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE trustguard;

CREATE TABLE IF NOT EXISTS analyses (
    id INT(11) NOT NULL AUTO_INCREMENT,
    input_type VARCHAR(50) NOT NULL,
    input_content TEXT DEFAULT NULL,
    trust_score INT(11) DEFAULT NULL,
    risk_level VARCHAR(50) DEFAULT NULL,
    classification VARCHAR(100) DEFAULT NULL,
    recommendation TEXT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS evidence (
    id INT(11) NOT NULL AUTO_INCREMENT,
    analysis_id INT(11) NOT NULL,
    category VARCHAR(100) NOT NULL,
    result VARCHAR(100) NOT NULL,
    explanation TEXT DEFAULT NULL,
    severity VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY analysis_id (analysis_id)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_general_ci;
