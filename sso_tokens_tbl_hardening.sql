ALTER TABLE tokens_tbl
    ADD COLUMN IF NOT EXISTS used boolean NOT NULL DEFAULT FALSE,
    ADD COLUMN IF NOT EXISTS used_at timestamp NULL,
    ADD COLUMN IF NOT EXISTS ip_address varchar(45) NULL,
    ADD COLUMN IF NOT EXISTS user_agent varchar(255) NULL;

CREATE INDEX IF NOT EXISTS idx_tokens_tbl_token ON tokens_tbl (token);
CREATE INDEX IF NOT EXISTS idx_tokens_tbl_expires_at ON tokens_tbl (expires_at);
