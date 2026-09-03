<?php $__env->startSection('title', 'Detail Penjualan'); ?>
<?php $__env->startSection('header', 'Detail Penjualan'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                Detail Transaksi #<?php echo e($penjualan->id); ?>

            </h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                <?php echo e($penjualan->created_at->translatedFormat('l, d F Y • H:i')); ?> WIB
            </p>
        </div>

        <a href="<?php echo e(route('penjualan.index')); ?>"
           class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        
        <div class="lg:col-span-1 space-y-5">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 p-6">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">
                    Informasi Transaksi
                </h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Kasir</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            <?php echo e($penjualan->user->name ?? '-'); ?>

                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 mb-1">Metode Pembayaran</p>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium
                            <?php if($penjualan->metode_pembayaran === 'CASH'): ?> bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400
                            <?php elseif($penjualan->metode_pembayaran === 'QRIS'): ?> bg-violet-50 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400
                            <?php else: ?> bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                            <?php endif; ?>">
                            <?php echo e($penjualan->metode_pembayaran); ?>

                        </span>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 mb-1">Status</p>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium
                            <?php echo e($penjualan->status === 'COMPLETED' 
                                ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' 
                                : 'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'); ?>">
                            <span class="w-1.5 h-1.5 rounded-full <?php echo e($penjualan->status === 'COMPLETED' ? 'bg-emerald-500' : 'bg-amber-500'); ?>"></span>
                            <?php echo e($penjualan->status); ?>

                        </span>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-gray-800">
                        <p class="text-xs text-gray-400 mb-1">Total Pembayaran</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">
                            Rp <?php echo e(number_format($penjualan->total_pembayaran, 0, ',', '.')); ?>

                        </p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-100 dark:border-gray-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800">
                    <h3 class="font-semibold text-gray-900 dark:text-white">
                        Item Transaksi
                        <span class="ml-2 text-sm font-normal text-gray-400">
                            (<?php echo e($penjualan->itemPenjualan->count()); ?> item)
                        </span>
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-800/50 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <th class="px-6 py-3">#</th>
                                <th class="px-6 py-3">Produk</th>
                                <th class="px-6 py-3 text-right">Harga</th>
                                <th class="px-6 py-3 text-center">Qty</th>
                                <th class="px-6 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            <?php $__empty_1 = true; $__currentLoopData = $penjualan->itemPenjualan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/30 transition">
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?php echo e($index + 1); ?>

                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            <?php echo e($item->produk->nama ?? 'Produk dihapus'); ?>

                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right text-gray-600 dark:text-gray-300">
                                        Rp <?php echo e(number_format($item->harga_satuan, 0, ',', '.')); ?>

                                    </td>
                                    <td class="px-6 py-4 text-sm text-center text-gray-600 dark:text-gray-300">
                                        <?php echo e($item->kuantitas); ?>

                                    </td>
                                    <td class="px-6 py-4 text-sm text-right font-medium text-gray-900 dark:text-white">
                                        Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?>

                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                        Tidak ada item dalam transaksi ini.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 dark:bg-gray-800/50">
                                <td colspan="4" class="px-6 py-4 text-right font-semibold text-gray-700 dark:text-gray-300">
                                    Total
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-lg text-gray-900 dark:text-white">
                                    Rp <?php echo e(number_format($penjualan->total_pembayaran, 0, ',', '.')); ?>

                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\pos_raul\resources\views/penjualan/show.blade.php ENDPATH**/ ?>