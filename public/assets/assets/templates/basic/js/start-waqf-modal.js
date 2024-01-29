// Setup modal values
let startWaqfModalBtn  = $("#startWaqfModalBtn");
let form     = startWaqfModalBtn.find("form");
const action = form[0] ? form[0].action : null;

$(document).on("click", ".startWaqfModalBtn", function () {    
	startWaqfModalBtn.modal("show");
});