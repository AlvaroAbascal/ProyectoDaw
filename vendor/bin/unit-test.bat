@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../endroid/quality/bin/unit-test
SET COMPOSER_RUNTIME_BIN_DIR=%~dp0
bash "%BIN_TARGET%" %*
