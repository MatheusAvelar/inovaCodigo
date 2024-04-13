import requests
from bs4 import BeautifulSoup

# URL do site do Bet365
url = "https://www.bet365.com/"

# Fazendo a solicitação HTTP
response = requests.get(url)

# Verificando se a solicitação foi bem-sucedida
if response.status_code == 200:
    # Parseando o HTML da página
    soup = BeautifulSoup(response.text, 'html.parser')
    
    # Extraindo o conteúdo que você precisa, por exemplo, o título da página
    title = soup.title.string
    print("Título da página:", title)

    # Você pode continuar a extrair outros dados ou manipular o HTML conforme necessário
else:
    print("Falha ao fazer a solicitação HTTP:", response.status_code)