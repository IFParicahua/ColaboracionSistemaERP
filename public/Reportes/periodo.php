<?php
require_once "stimulsoft/helper.php";
?>
<!DOCTYPE html>

<html>
<head>
	<title>Report.mrt - Viewer</title>
	<link rel="stylesheet" type="text/css" href="css/stimulsoft.viewer.office2013.whiteblue.css">
	<script type="text/javascript" src="scripts/stimulsoft.reports.js"></script>
	<script type="text/javascript" src="scripts/stimulsoft.viewer.js"></script>

	<?php
		$options = StiHelper::createOptions();
		$options->handler = "handler.php";
		$options->timeout = 30;
		StiHelper::initialize($options);
	?>
	<script type="text/javascript">
		function Start() {
            var usuario = <?php echo $_GET['usuario']?>;
            //var empresa = <?php //echo $_GET['empresa']?>//;
            var gestion = <?php echo $_GET['gestion']?>;
			var report = new Stimulsoft.Report.StiReport();
			report.loadFile("reports/Periodo.mrt");

			report.dictionary.variables.getByName("usuario").valueObject = +usuario;
			// report.dictionary.variables.getByName("emp").valueObject = +empresa;
			report.dictionary.variables.getByName("ges").valueObject = +gestion;

			var options = new Stimulsoft.Viewer.StiViewerOptions();
			var viewer = new Stimulsoft.Viewer.StiViewer(options, "StiViewer", false);

			viewer.onBeginProcessData = function (args, callback) {
				<?php StiHelper::createHandler(); ?>
			}

			viewer.report = report;
			viewer.renderHtml("viewerContent");
		}
	</script>
</head>
<body onload="Start()">
	<div id="viewerContent"></div>
</body>
</html>