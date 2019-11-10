/* Login & Register From */
$(".form")
	.find("input, textarea")
	.on("keyup blur focus", function(e) {
		var $this = $(this),
			label = $this.prev("label");

		if (e.type === "keyup") {
			if ($this.val() === "") {
				label.removeClass("active highlight");
			} else {
				label.addClass("active highlight");
			}
		} else if (e.type === "blur") {
			if ($this.val() === "") {
				label.removeClass("active highlight");
			} else {
				label.removeClass("highlight");
			}
		} else if (e.type === "focus") {
			if ($this.val() === "") {
				label.removeClass("highlight");
			} else if ($this.val() !== "") {
				label.addClass("highlight");
			}
		}
	});

$(".tab a").on("click", function(e) {
	e.preventDefault();

	$(this)
		.parent()
		.addClass("active");
	$(this)
		.parent()
		.siblings()
		.removeClass("active");

	if (!$("body#feeds").length) {
		target = $(this).attr("href");
		$(".tab-content > div")
			.not(target)
			.hide();
		$(target).fadeIn(600);
	}
});

/* Feed Filters*/
$(".all-feeds").on("click", function(e) {
	$(".feed_item").fadeIn(500);
});

$(".filter a").on("click", function(e) {
	filter = $(this).data("filter-by");

	e.preventDefault();
	$(".feed_item").hide();
	$(".feed_item").each(function() {
		title = $(this)
			.find(".title a")
			.text();
		desc = $(this)
			.find(".desc")
			.text();
		if (title.indexOf(filter) != -1 || desc.indexOf(filter) != -1) {
			$(this).fadeIn(500);
		}
	});
});

/* Form Validation */
initValidateForm($("#form_register"), $("#errors_register"), null);
initValidateForm($("#form_login"), $("#errors_login"), "feeds");

function initValidateForm(frm, errors, redirect) {
	frm.submit(function(e) {
		e.preventDefault();
		$.ajax({
			type: frm.attr("method"),
			url: frm.attr("action"),
			data: frm.serialize(),
			dataType: "json",
			success: function(data) {
				if (data.status) {
					errors
						.addClass("success")
						.html(data.message)
						.show();
					frm.trigger("reset");
					frm.find("label").removeClass("active");
					if (redirect) {
						window.location.replace(base_url + "index.php/" + redirect);
					}
				} else {
					errors
						.html(data.errors)
						.removeClass("success")
						.show();
				}
			},
			error: function(data) {
				console.log("Oops ! Spark in plug !");
				console.log(data);
			}
		});
	});
}

/* Email Check on the Fly*/
$("#form_register input[name=email]").keyup(function() {
	email_message = $(".email_message");
	if (!$(this).val()) {
		email_message.hide();
		return false;
	}
	$.ajax({
		type: "post",
		url: base_url + "index.php/register/checkEmailOnTheFly/",
		data: { email: $(this).val() },
		dataType: "json",
		success: function(data) {
			if (data.status) {
				email_message
					.html(data.message)
					.show()
					.removeClass("fail")
					.addClass("success");
			} else {
				email_message
					.html(data.message)
					.show()
					.removeClass("success")
					.addClass("fail");
			}
		},
		error: function(data) {
			console.log("Oops ! Spark in plug !");
			console.log(data);
		}
	});
});
