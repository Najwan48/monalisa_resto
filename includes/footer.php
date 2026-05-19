
<?php ?>
<footer>
    <div class="container">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 3rem; text-align: left; margin-bottom: 3rem; padding-bottom: 3rem; border-bottom: 1px solid rgba(255,255,255,0.07);">
            <div>
                <p style="font-family: 'Cormorant Garamond', serif; font-size: 1.5rem; color: #fff; margin-bottom: 1rem; letter-spacing: 0.05em;">Monalisa Resto</p>
                <p style="line-height: 1.8; max-width: 260px;">Menyajikan cita rasa autentik khas Jawa Tengah dengan resep warisan keluarga yang terus dijaga kelestariannya.</p>
            </div>
            <div>
                <p style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: rgba(255,255,255,0.35); margin-bottom: 1rem;">Jam Operasional</p>
                <p style="color: rgba(255,255,255,0.8);"><?= h($kontak['jam_operasional'] ?? 'Setiap Hari: 07.00 – 22.00 WIB') ?></p>
            </div>
            <div>
                <p style="font-size: 0.7rem; font-weight: 700; letter-spacing: 0.2em; text-transform: uppercase; color: rgba(255,255,255,0.35); margin-bottom: 1rem;">Kontak & Lokasi</p>
                <p style="margin-bottom: 0.5rem;"><a href="https://maps.app.goo.gl/J5wMuZqK8dnd5nu19" target="_blank" style="color: rgba(255,255,255,0.8); text-decoration: none;"><?= h($kontak['alamat'] ?? 'Jl. Raya Tajur No. 30, Kota Bogor') ?></a></p>
                <p style="color: rgba(255,255,255,0.8);">Telp: <?= h($kontak['telepon'] ?? '0812-3456-7890') ?></p>
            </div>
        </div>
        <p style="font-size: 0.8rem;">&copy; <?= date('Y') ?> Monalisa Resto. Hak Cipta Dilindungi.</p>
    </div>
</footer>

<?php
$wa_raw = !empty($kontak['whatsapp']) ? $kontak['whatsapp'] : ($kontak['telepon'] ?? '081234567890');
$clean_wa = preg_replace('/[^0-9]/', '', $wa_raw);
$wa_number = $clean_wa;
if (strpos($clean_wa, '0') === 0) {
    $wa_number = '62' . substr($clean_wa, 1);
}
?>
<a href="https://wa.me/<?= h($wa_number) ?>" class="whatsapp-float" target="_blank" title="Hubungi kami via WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

</body>
</html>
