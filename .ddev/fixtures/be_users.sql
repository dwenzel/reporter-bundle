# Default admin user (password = password)
SET @username := 'admin';
SET @password := '$argon2i$v=19$m=65536,t=16,p=1$cndIdWlKajNQNlV1UklXLg$IDKHDffigetxxdlvUuuG27bLIld1m1E/6IYCcihyTws';

INSERT INTO be_users (uid, username, password, admin, disable)
VALUES (1, @username, @password, 1, 0)
ON DUPLICATE KEY UPDATE username = @username,
                        password = @password,
                        disable = 0;