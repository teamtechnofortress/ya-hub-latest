$('.opt-btn').first().addClass('active');

$('.opt-btn').click(function () {
    var $this = $(this);
    $siblings = $this.parent().children(),
        position = $siblings.index($this);
    console.log(position);

    $('.content .ya-form').removeClass('active').eq(position).addClass('active');

    $siblings.removeClass('active');
    $this.addClass('active');
})
$(".media-toggle-btn").on("click", function () {
    $(".media-toggle").toggle(":visibility");
})
$(".role-toggle").on("change", function () {
    if (parseInt($(this).val()) == 3) {
        $(".code-area-agency").hide();
        $(".agency-name-area").show();
    }
    if (parseInt($(this).val()) == 2 || parseInt($(this).val()) == 4) {
        $(".code-area-agency").show();
        $(".agency-name-area").hide();
    }
})
$('.p-opt-btn').first().addClass('active');

$('.p-opt-btn').click(function () {
    var $this = $(this);
    $siblings = $this.parent().children(),
        position = $siblings.index($this);
    console.log(position);

    $('.pro-content .pro-row').removeClass('active').eq(position).addClass('active');

    $siblings.removeClass('active');
    $this.addClass('active');
})

$(".terms-agree-btn").on("click", function () {
    $(".register-submit").prop('disabled', function (i, v) { return !v; });
})