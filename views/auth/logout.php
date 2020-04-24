<?php
session_start();

session_unset();
session_destroy();

header("Location: /eosge3/auth");
