CREATE TABLE notes (
    id UUID NOT NULL,
    content text NULL,
    code VARCHAR(63) NOT NULL,
    cipher text NULL,
    init_vector VARCHAR(63) NULL,
    encoding VARCHAR(63) NULL,
    views_limit SMALLINT NULL,
    storage_time_expires_at TIMESTAMP(0) WITH TIME ZONE NULL,
    PRIMARY KEY(id)
);

CREATE TABLE note_views_counters (
    note_id UUID NOT NULL,
    value INTEGER NOT NULL,
    PRIMARY KEY(note_id)
);
