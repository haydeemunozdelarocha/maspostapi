<?php
$_ENV["mode"]="development";

if ($_ENV["mode"] === "development") {
    $_ENV["MASPOST_DB_USERNAME"] = getenv("MASPOST_DB_USER_LOCAL");
    $_ENV["MASPOST_DB_PASSWORD"] = getenv("MASPOST_DB_PASSWORD_LOCAL");
    $_ENV["MASPOST_URL"] = getenv("MASPOST_URL");
}

if ($_ENV["mode"] === "production") {
    $_ENV["MASPOST_DB_USERNAME"] = getenv("MASPOST_DB_USER");
    $_ENV["MASPOST_DB_PASSWORD"] = getenv("MASPOST_DB_PASSWORD");
    $_ENV["MASPOST_URL"] = getenv("MASPOST_URL");
}
