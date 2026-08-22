<?php

namespace App\Services;

use App\Models\BookTour;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingDocumentService
{
    public function __construct(private SimplePdfService $pdf)
    {
    }

    public function confirmation(BookTour $booking): Response
    {
        $booking->loadMissing(['tour', 'user']);
        $filename = 'booking-' . $booking->display_code . '.pdf';

        return $this->pdfResponse($this->pdf->make($this->confirmationLines($booking), 'Miu Travel - Booking Confirmation'), $filename);
    }

    public function exportPdf(Collection $bookings): Response
    {
        $lines = ['BOOKING EXPORT', 'Total: ' . $bookings->count(), ''];

        foreach ($bookings as $booking) {
            $lines[] = implode(' | ', [
                $booking->display_code,
                $this->statusLabel($booking),
                $booking->b_name,
                $booking->b_phone,
                'Staff: ' . (optional($booking->assignedStaff)->name ?: 'Unassigned'),
                optional($booking->tour)->t_title ?: 'No tour',
                'Start: ' . $this->date($booking->b_start_date),
                'Guests: ' . $booking->total_guests,
                'Total: ' . number_format($booking->total_price, 0, ',', '.') . ' VND',
            ]);

            if ($booking->b_cancel_reason) {
                $lines[] = 'Cancel reason: ' . $booking->b_cancel_reason;
            }
        }

        return $this->pdfResponse($this->pdf->make($lines, 'Miu Travel - Booking Export'), 'booking-export-' . now()->format('Ymd-His') . '.pdf');
    }

    public function exportCsv(Collection $bookings): StreamedResponse
    {
        $filename = 'booking-export-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($bookings) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'Mã booking',
                'Trạng thái',
                'Tour',
                'Khách hàng',
                'Email',
                'Số điện thoại',
                'Ngày đi',
                'Ngày về',
                'Nhân viên phụ trách',
                'Tổng khách',
                'Tổng tiền',
                'Ghi chú nội bộ',
                'Lý do hủy',
            ]);

            foreach ($bookings as $booking) {
                fputcsv($handle, [
                    $booking->display_code,
                    $this->statusLabel($booking),
                    optional($booking->tour)->t_title,
                    $booking->b_name,
                    $booking->b_email,
                    $booking->b_phone,
                    $this->date($booking->b_start_date),
                    $this->date($booking->b_end_date),
                    optional($booking->assignedStaff)->name,
                    $booking->total_guests,
                    $booking->total_price,
                    $booking->b_internal_note,
                    $booking->b_cancel_reason,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function confirmationLines(BookTour $booking): array
    {
        $lines = [
            'Booking code: ' . $booking->display_code,
            'Status: ' . $this->statusLabel($booking),
            'Tour: ' . (optional($booking->tour)->t_title ?: 'No tour'),
            'Customer: ' . $booking->b_name,
            'Email: ' . $booking->b_email,
            'Phone: ' . $booking->b_phone,
            'Pickup address: ' . ($booking->b_address ?: '---'),
            'Start date: ' . $this->date($booking->b_start_date),
            'End date: ' . $this->date($booking->b_end_date),
            'Adults: ' . (int) $booking->b_number_adults . ' x ' . number_format((int) $booking->b_price_adults, 0, ',', '.') . ' VND',
            'Children 6-12: ' . (int) $booking->b_number_children . ' x ' . number_format((int) $booking->b_price_children, 0, ',', '.') . ' VND',
            'Children 2-6: ' . (int) $booking->b_number_child6 . ' x ' . number_format((int) $booking->b_price_child6, 0, ',', '.') . ' VND',
            'Children under 2: ' . (int) $booking->b_number_child2 . ' x ' . number_format((int) $booking->b_price_child2, 0, ',', '.') . ' VND',
            'Total guests: ' . $booking->total_guests,
            'Total price: ' . number_format($booking->total_price, 0, ',', '.') . ' VND',
        ];

        if ($booking->b_note) {
            $lines[] = 'Customer note: ' . $booking->b_note;
        }

        if ($booking->b_cancel_reason) {
            $lines[] = 'Cancel reason: ' . $booking->b_cancel_reason;
        }

        return $lines;
    }

    private function pdfResponse(string $content, string $filename): Response
    {
        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function statusLabel(BookTour $booking): string
    {
        return BookTour::STATUS[(int) $booking->b_status] ?? 'Không rõ';
    }

    private function date($value): string
    {
        return $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : '---';
    }
}
