document.getElementById('ddlConvenios').value = '60';
document.getElementById('ddlConvenios').dispatchEvent(new Event('change'));
document.getElementById('ddlDataEvento').value = '78'; //Data 82 - 83 - 84 - 85 - 86
document.getElementById('ddlDataEvento').dispatchEvent(new Event('change'));
document.getElementById('ddlCPAS').value = '37';
document.getElementById('ddlCPAS').dispatchEvent(new Event('change'));
// Execute esse código no contexto da página onde o link está presente
document.getElementById('lnkNewCaptcha').click();