from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.support.ui import Select

# Configuração do Selenium WebDriver
driver = webdriver.Chrome()  # Substitua por Firefox() se preferir
driver.get("https://proeis.rj.gov.br/Default.aspx")

try:
    # Espera o campo de TIPO (select) estar presente antes de continuar
    tipoacesso_field = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.ID, "ddlTipoAcesso"))
    )
    
    # Seleciona o valor "ID" no select
    select_tipoacesso = Select(tipoacesso_field)
    select_tipoacesso.select_by_value("ID")

    # Espera o campo de login estar presente antes de continuar
    username_field = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.ID, "txtLogin"))
    )
    
    # Encontra e preenche o campo de senha
    password_field = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.ID, "txtSenha"))
    )

    # Preenche os campos de login e senha
    username_field.send_keys("43670164")
    password_field.send_keys("36112233")

    # Submete o formulário (pressiona Enter no campo de senha)
    password_field.send_keys(Keys.RETURN)

    # Aguarda o redirecionamento para a página do voluntário
    WebDriverWait(driver, 10).until(
        EC.url_to_be("https://proeis.rj.gov.br/FrmMenuVoluntario.aspx")
    )
    print("Login bem-sucedido, redirecionado para a nova página!")

    # Verifica se o botão "btnEscala" está presente na página
    button = WebDriverWait(driver, 10).until(
        EC.presence_of_element_located((By.ID, "btnEscala"))
    )
    if button:
        print("Botão encontrado!")
    else:
        print("Botão não encontrado.")

except Exception as e:
    print(f"Ocorreu um erro: {e}")

finally:
    # Fecha o navegador
    driver.quit()
