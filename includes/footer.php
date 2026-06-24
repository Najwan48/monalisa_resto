
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
$wa_raw = $kontak['whatsapp'] ?? $kontak['telepon'] ?? '';
$clean_wa = preg_replace('/[^0-9]/', '', $wa_raw);
$wa_number = $clean_wa;
if (strpos($clean_wa, '0') === 0) {
    $wa_number = '62' . substr($clean_wa, 1);
}
?>
<?php if (!empty($wa_number)): ?>
<div class="wa-chat-popup" id="waChatPopup">
    <div class="wa-chat-header">
        <div class="wa-chat-avatar">
            <i class="ri-restaurant-line"></i>
        </div>
        <div class="wa-chat-meta">
            <span class="wa-chat-title">Monalisa Resto</span>
            <span class="wa-chat-status">Online</span>
        </div>
        <button class="wa-chat-close" id="waChatClose">&times;</button>
    </div>
    <div class="wa-chat-body">
        <div class="wa-chat-bubble">
            <p>Selamat datang di Monalisa Resto.</p>
            <p>Ada yang bisa kami bantu? Anda dapat menanyakan detail menu, memesan menu, atau lokasi kami.</p>
        </div>
        <div class="wa-chat-quick-options">
            <button class="wa-quick-btn" data-msg="Halo Monalisa Resto, saya ingin menanyakan detail menu.">Detail Menu</button>
            <button class="wa-quick-btn" data-msg="Halo Monalisa Resto, saya ingin melakukan pemesanan menu.">Memesan Menu</button>
            <button class="wa-quick-btn" data-msg="Halo Monalisa Resto, saya ingin menanyakan alamat dan lokasi restoran.">Lokasi</button>
        </div>
    </div>
    <div class="wa-chat-footer">
        <input type="text" class="wa-chat-input" id="waChatInput" placeholder="Ketik pesan Anda di sini...">
        <button class="wa-chat-send" id="waChatSend" data-phone="<?= escapeHtml($wa_number) ?>">
            <i class="ri-send-plane-line"></i>
        </button>
    </div>
</div>

<button class="whatsapp-float" id="waFloatBtn" title="Hubungi kami via WhatsApp">
    <i class="ri-whatsapp-line"></i>
</button>
<?php endif; ?>

<script type="module" src="assets/js/parallax.js?v=2"></script>

</body>
</html>
