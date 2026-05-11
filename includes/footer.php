<?php ?>
<footer>
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem; text-align: left; margin-bottom: 2rem;">
            <div>
                <h3 style="color: var(--secondary-color);">Monalisa Resto</h3>
                <p>Menyajikan cita rasa autentik khas Jawa Tengah dengan resep warisan keluarga.</p>
            </div>
            <div>
                <h3 style="color: var(--secondary-color);">Jam Operasional</h3>
                <p><?= h($kontak['jam_operasional'] ?? 'Setiap Hari: 07.00 - 22.00 WIB') ?></p>
            </div>
            <div>
                <h3 style="color: var(--secondary-color);">Kontak &amp; Lokasi</h3>
                <p><a href="https://maps.app.goo.gl/J5wMuZqK8dnd5nu19" target="_blank" style="color: inherit; text-decoration: none;"><?= h($kontak['alamat'] ?? 'Jl. Raya Tajur No. 30, Kota Bogor') ?></a></p>
                <p>Telp: <?= h($kontak['telepon'] ?? '0812-3456-7890') ?></p>
            </div>
        </div>
        <div style="border-top: 1px solid rgba(255,255,255,0.1); padding-top: 1rem;">
            <p>&copy; <?= date('Y'); ?> Monalisa Resto. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>
