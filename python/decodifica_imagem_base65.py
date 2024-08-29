import pytesseract
from PIL import Image, ImageEnhance, ImageFilter, ImageOps

# Carregar a imagem do arquivo
image = Image.open('captcha_image.png')

# Converter para escala de cinza
image = image.convert('L')

# Ajustar o brilho e o contraste
#enhancer = ImageEnhance.Contrast(image)
#image = enhancer.enhance(2.5)  # Aumentar o contraste

#brightness = ImageEnhance.Brightness(image)
#image = brightness.enhance(2.2)  # Aumentar o brilho

# Aplicar filtro de Unsharp Mask para realçar bordas
image = image.filter(ImageFilter.UnsharpMask(radius=2, percent=150, threshold=3))

# Binarizar a imagem (conversão para preto e branco)
threshold = 128  # Ajuste o valor conforme necessário
image = image.point(lambda p: p > threshold and 255)

# Redimensionar a imagem
image = image.resize((image.width * 2, image.height * 2), Image.Resampling.BOX)

# Inverter as cores
#image = ImageOps.invert(image)

# Binarizar a imagem
#image = image.point(lambda p: p > 100 and 255)

# Mostrar a imagem processada
image.show()

# Usar o pytesseract para extrair o texto da imagem
captcha_text = pytesseract.image_to_string(image, config='--psm 6 --oem 1', lang='eng')

print("Texto extraído do CAPTCHA:", captcha_text)

