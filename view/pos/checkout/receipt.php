<?php
/**
 *
 */

$this->layout_file = sprintf('%s/view/_layout/html-pos.php', APP_ROOT);

?>

<form method="post">

<div class="container mt-4">
<div class="row justify-content-center">
<div class="col-md-8">
<div class="card">
	<h1 class="card-header">Print Receipt</h1>
	<div class="card-body">

		<div class="form-group">
			<h2>Email</h2>
			<form method="post">
			<input name="sale_id" type="hidden" value="{{ Sale.id }}">
			<div class="input-group">
				<input class="form-control" name="receipt-email" placeholder="your@email.com" type="email">
				<div class="input-group-append">
					<button class="btn btn-outline-secondary" name="a" value="send-email">Send Receipt</button>
				</div>
			</div>
			</form>
		</div>

		<div class="form-group">
			<h2>Text</h2>
			<form method="post">
			<input name="sale_id" type="hidden" value="{{ Sale.id }}">
			<div class="input-group">
				<input class="form-control" name="receipt-phone" placeholder="(###) ###-####" type="text">
				<div class="input-group-append">
					<button class="btn btn-outline-secondary" name="a" value="send-phone">Send Receipt</button>
				</div>
			</div>
			</form>
		</div>

		<form method="post">
		<div class="form-group">
			<h2>Print It</h2>
			<div class="input-group">
				<select class="form-control" id="printer-list">
					<option>- Select Printer -</option>
					{% for p in printer_list %}
						<option data-local-link="{{ p.link }}" value="{{ p.type }}">{{ p.name }}</option>
					{% endfor %}
				</select>
				<div class="input-group-append">
					<button class="btn btn-outline-warning" formtarget="openthc-print-window" id="send-print" name="a" type="submit" value="send-print"><i class="fas fa-printer"></i> Print Receipt</button>
				</div>
			</div>
			<p>Warning: Printing kills trees</p>
		</div>
		</form>
	</div>


	<div class="card-footer">
		<button class="btn btn-outline-primary" name="a" value="send-blank">No Receipt</button>
	</div>

</div>
</div>
</div>
</div>

</form>


<script>
function btnErrorFlash($btn)
{
	$btn.addClass('btn-outline-danger');
	setTimeout(function() {
		$btn.removeClass('btn-outline-danger');
	}, 1500);

}

$(function() {

	$('#send-print').on('click', function() {

		var $btn = $(this);
		var $sel = $('#printer-list').find(':selected');
		var val = $('#printer-list').val();

		// @todo if the selected printer is marked as "local-http"
		// Then we have to capture the PDF from the server
		// And then POST that PDF document to the specific server
		// Hopefully it works, it must be running our custom "print" server
		//var lpu = $('#print-list').val();

		// What ?  Popup?  Prompt for AIR-Print or Whatever?

		switch (val) {
		case 'air':

			btnErrorFlash($btn);

			return false;

			break;

		case 'lpd':

			// Emit an Application Specific Event for Android, Electron or iOS to Catch?
			btnErrorFlash($btn);

			return false;

			break;

		case 'pdf':

			// var $F = $(this).closest('form');

			var opts = [];
			opts.push('top=' + (window.screenTop + 64));
			opts.push('left=' + (window.screenLeft + 64));
			opts.push('width=' + (window.outerWidth - 128));
			opts.push('height=' + (window.outerHeight - 256));
			opts.push('location=yes');
			opts.push('scrollbars=yes');

			var w = window.open('/loading.html', 'openthc-print-window', opts.join(','));
			w.addEventListener('load', function() {
				console.log('onLoad!');
				setTimeout(function() {
					w.print();
				}, 1000);
			}, true);
			w.addEventListener('afterprint', function() {
				console.log('onAfterPrint!');
				// w.close();
			});

			// Do Nothing, Submit Form
			// w.onloadend = function() {
			// 	console.log('onloadend!');
			// };
			// w.print();
			break;

		case 'rpi':

			var lpu = $sel.data('local-link');
			if (lpu) {
				var url = window.location;
				POS.Printer.printLocalNetwork(url, lpu);
			} else {
				btnErrorFlash($btn);
			}

			return false;

			break;

		}

	});
});
</script>
