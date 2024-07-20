# REST API Jakarta Smart

REST API Jakarta Smart adalah layanan API yang menyediakan data dan informasi terkait kota Jakarta. API ini dirancang untuk membantu pengembang dalam mengakses berbagai data yang berkaitan dengan Jakarta, termasuk informasi lalu lintas, cuaca, transportasi publik, dan lain-lain.

## Fitur Utama

- **Informasi Lalu Lintas:** Data real-time tentang kondisi lalu lintas di Jakarta.
- **Cuaca:** Informasi cuaca harian dan perkiraan cuaca mingguan.
- **Transportasi Publik:** Data jadwal dan rute transportasi publik seperti bus, kereta, dan MRT.
- **Berita Lokal:** Berita terkini dari sumber-sumber terpercaya tentang Jakarta.
- **Layanan Darurat:** Informasi kontak layanan darurat di Jakarta.

## Instalasi

Untuk menginstal proyek ini secara lokal, ikuti langkah-langkah berikut:

1. Clone repositori ini:
    ```sh
    git clone https://github.com/Versa-Morph/restapi-jakarta-smart.git
    ```

2. Masuk ke direktori proyek:
    ```sh
    cd restapi-jakarta-smart
    ```

3. Instal dependensi yang diperlukan:
    ```sh
    npm install
    ```

4. Buat file `.env` dari template `.env.example` dan sesuaikan konfigurasi:
    ```sh
    cp .env.example .env
    ```

5. Jalankan server:
    ```sh
    npm start
    ```

## Penggunaan

Setelah server berjalan, Anda dapat mengakses API melalui `http://localhost:3000`. Berikut adalah beberapa endpoint yang tersedia:

- **GET /traffic:** Mendapatkan informasi lalu lintas.
- **GET /weather:** Mendapatkan informasi cuaca terkini.
- **GET /public-transport:** Mendapatkan data transportasi publik.
- **GET /news:** Mendapatkan berita terbaru.
- **GET /emergency-contacts:** Mendapatkan informasi kontak layanan darurat.

### Contoh Penggunaan

**Mengambil Informasi Lalu Lintas**
```sh
curl -X GET http://localhost:3000/traffic
```
**Mengambil Informasi Lalu Lintas**
```sh
curl -X GET http://localhost:3000/weather
```

## Kontribusi

Kami menerima kontribusi dari siapa saja yang ingin berkontribusi pada pengembangan proyek ini. Untuk berkontribusi, silakan ikuti langkah-langkah berikut:

1. Fork repositori ini.
2. Buat branch fitur baru (`git checkout -b fitur-anda`).
3. Commit perubahan Anda (`git commit -am 'Tambah fitur ABC'`).
4. Push ke branch tersebut (`git push origin fitur-anda`).
5. Buat Pull Request baru.

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## Kontak

Untuk informasi lebih lanjut atau pertanyaan, silakan hubungi kami di [versamorph.dev@gmail.com](mailto:versamorph.dev@gmail.com).

