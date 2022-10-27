@ECHO OFF
setlocal DISABLEDELAYEDEXPANSION
SET BIN_TARGET=%~dp0/../gettext/languages/bin/export-plural-rules.php
sh "%BIN_TARGET%" %*
