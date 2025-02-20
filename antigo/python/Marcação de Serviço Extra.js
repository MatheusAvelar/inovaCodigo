document.getElementById('ddlConvenios').value = '60';
document.getElementById('ddlConvenios').dispatchEvent(new Event('change'));
document.getElementById('ddlDataEvento').value = '88'; //Data 82 - 83 - 84 - 85 - 86
document.getElementById('ddlDataEvento').dispatchEvent(new Event('change'));
document.getElementById('ddlCPAS').value = '37';
document.getElementById('ddlCPAS').dispatchEvent(new Event('change'));
document.getElementById('lnkNewCaptcha').click();