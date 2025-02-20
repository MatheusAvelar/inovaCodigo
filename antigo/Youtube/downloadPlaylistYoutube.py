import yt_dlp

# URL da playlist
playlist_url = "Link da Playlist"

# Local para salvar os vídeos
save_path = "./downloads"

# Caminho para o arquivo de cookies
cookies_file = "./cookies.txt"

# Opções do yt-dlp
ydl_opts = {
    'format': 'bestaudio/best',
    'extractaudio': True,
    'audioquality': 1,
    'outtmpl': f'{save_path}/%(title)s.%(ext)s',
    'postprocessors': [{
        'key': 'FFmpegExtractAudio',
        'preferredcodec': 'mp3',
        'preferredquality': '0',
    }],
    'ffmpeg_location': r'C:\ffmpeg\bin\ffmpeg.exe',
    'cookiefile': cookies_file,
    'http_headers': {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36',
    },
    'allow_unplayable_formats': True,
    'nocheckcertificate': True,
    'ignoreerrors': True,
    #'verbose': True,
}

try:
    with yt_dlp.YoutubeDL(ydl_opts) as ydl:
        ydl.download([playlist_url])
    print("Download da playlist concluído!")
except Exception as e:
    print(f"Erro ao processar a playlist: {e}")
