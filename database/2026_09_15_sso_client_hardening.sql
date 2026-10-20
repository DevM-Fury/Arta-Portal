BEGIN;

ALTER TABLE systems
    ADD COLUMN IF NOT EXISTS sso_enabled boolean NOT NULL DEFAULT FALSE,
    ADD COLUMN IF NOT EXISTS sso_client_id varchar(100) NULL,
    ADD COLUMN IF NOT EXISTS sso_client_secret_hash varchar(255) NULL;

CREATE UNIQUE INDEX IF NOT EXISTS ux_systems_sso_client_id
    ON systems (sso_client_id)
    WHERE sso_client_id IS NOT NULL;

ALTER TABLE tokens_tbl
    ADD COLUMN IF NOT EXISTS used boolean NOT NULL DEFAULT FALSE,
    ADD COLUMN IF NOT EXISTS used_at timestamp NULL,
    ADD COLUMN IF NOT EXISTS ip_address varchar(45) NULL,
    ADD COLUMN IF NOT EXISTS user_agent varchar(255) NULL;

CREATE INDEX IF NOT EXISTS idx_tokens_tbl_token
    ON tokens_tbl (token);

CREATE INDEX IF NOT EXISTS idx_tokens_tbl_expires_at
    ON tokens_tbl (expires_at);

CREATE INDEX IF NOT EXISTS idx_tokens_tbl_system_used
    ON tokens_tbl (system_id, used);

COMMIT;
