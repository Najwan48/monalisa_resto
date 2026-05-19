
<?php ?>
<footer style="border-top: 1px solid rgba(255,255,255,0.08);">
    <div class="container" style="text-align: center; max-width: 600px;">
        <div style="margin-bottom: 2.5rem; padding-bottom: 2.5rem; border-bottom: 1px solid rgba(255,255,255,0.07);">
            <p style="font-family: 'Cormorant Garamond', serif; font-size: 1.6rem; color: #fff; margin-bottom: 0.75rem; letter-spacing: 0.05em;">Monalisa Resto</p>
            <p style="line-height: 1.8; color: rgba(255,255,255,0.6); margin-bottom: 1.5rem;">Menyajikan cita rasa autentik khas Jawa Tengah dengan resep warisan keluarga yang terus dijaga kelestariannya.</p>
            <a href="kontak.php" style="color: var(--primary-light); text-decoration: none; font-weight: 600; border-bottom: 1px solid var(--primary-light); padding-bottom: 2px;">
                Hubungi Kami & Lokasi
            </a>
        </div>
        <p style="font-size: 0.8rem; color: rgba(255,255,255,0.4);">&copy; <?= date('Y') ?> Monalisa Resto. Hak Cipta Dilindungi.</p>
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
