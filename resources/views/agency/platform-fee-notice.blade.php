<div class="alert alert-info" role="note">
    <i class="fas fa-percentage mr-1" aria-hidden="true"></i>
    <strong>Phí nền tảng hiện hành: {{ number_format(\App\Models\PlatformFeeSetting::currentBasisPoints() / 100, 2, ',', '.') }}%</strong>
    <div class="small mt-1">Áp dụng chung cho đơn mới của mọi đại lý. Phí khấu trừ từ giá trị đơn khi hoàn tất, không cộng vào tiền khách thanh toán. Đơn đã tạo giữ nguyên tỷ lệ được lưu lúc đặt.</div>
</div>
