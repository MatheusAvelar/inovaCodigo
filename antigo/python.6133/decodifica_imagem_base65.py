import pytesseract
from PIL import Image, ImageEnhance, ImageFilter, ImageOps

# Carregar a imagem do arquivo
image = Image.open('captcha_image.png')

# Converter para escala de cinza
image = image.convert('L')

# Ajustar o brilho e o contraste
enhancer = ImageEnhance.Contrast(image)
image = enhancer.enhance(2.0)  # Ajuste do contraste

brightness = ImageEnhance.Brightness(image)
image = brightness.enhance(2.0)  # Ajuste do brilho

# Aplicar filtro para redução de ruído
image = image.filter(ImageFilter.MedianFilter(size=3))

# Redimensionar a imagem antes de aplicar os filtros
image = image.resize((int(image.width * 1.5), int(image.height * 1.5)), Image.Resampling.BOX)

# Binarizar a imagem (conversão para preto e branco)
threshold = 150  # Ajuste fino do limite
image = image.point(lambda p: p > threshold and 255)

# Mostrar a imagem processada
image.show()

# Usar o pytesseract para extrair o texto da imagem com whitelist de caracteres
captcha_text = pytesseract.image_to_string(image, config='--psm 7 --oem 1 -c tessedit_char_whitelist=abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')

print("Texto extraído do CAPTCHA:", captcha_text)
