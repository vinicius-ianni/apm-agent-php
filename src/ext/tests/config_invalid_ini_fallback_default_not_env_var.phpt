--TEST--
When value in ini is invalid the fallback is the default and not environment variable
--SKIPIF--
<?php if ( ! extension_loaded( 'elastic_apm' ) ) die( 'skip'.'Extension elastic_apm must be installed' ); ?>
--ENV--
ELASTIC_APM_LOG_LEVEL_STDERR=CRITICAL
ELASTIC_APM_LOG_LEVEL_FILE=CRITICAL
ELASTIC_APM_ASSERT_LEVEL=O_n
--INI--
elastic_apm.log_level_file=not a valid log level
--FILE--
<?php
declare(strict_types=1);
require __DIR__ . '/../tests_util/tests_util.php';

elasticApmAssertSame("getenv('ELASTIC_APM_LOG_LEVEL_FILE')", getenv('ELASTIC_APM_LOG_LEVEL_FILE'), 'CRITICAL');

elasticApmAssertSame("getenv('ELASTIC_APM_ASSERT_LEVEL')", getenv('ELASTIC_APM_ASSERT_LEVEL'), 'O_n');

elasticApmAssertSame("ini_get('elastic_apm.log_level_file')", ini_get('elastic_apm.log_level_file'), 'not a valid log level');

// log_level_file is set in ini albeit the value is invalid so it does fall back on env vars
elasticApmAssertSame("elastic_apm_get_config_option_by_name('log_level_file')", elastic_apm_get_config_option_by_name('log_level_file'), ELASTIC_APM_LOG_LEVEL_NOT_SET);

// assert_level is not set in ini so it does fall back on env vars
elasticApmAssertSame("elastic_apm_get_config_option_by_name('assert_level')", elastic_apm_get_config_option_by_name('assert_level'), ELASTIC_APM_ASSERT_LEVEL_O_N);

echo 'Test completed'
?>
--EXPECT--
Test completed
