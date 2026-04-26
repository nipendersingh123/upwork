$(document).ready(function () {
    let editingCard = null;

    // Open Modal for Adding
    $('#add-milestone-btn').click(function () {
        editingCard = null;
        $('#milestone-modal h3').text('Add Milestone');
        $('#save-milestone-btn').text('Save');

        // Clear inputs
        $('#m-title').val('');
        $('#m-desc').val('');
        $('#m-price').val('');
        $('#m-revision').val('');
        $('#m-delivery').val('');

        $('#milestone-modal').removeClass('hidden');
    });

    // Close Modal
    $('#close-modal-btn, #modal-backdrop').click(function () {
        $('#milestone-modal').addClass('hidden');
    });

    // Save/Update Milestone
    $('#save-milestone-btn').click(function () {
        const title = $('#m-title').val();
        const desc = $('#m-desc').val();
        const price = $('#m-price').val();
        const revision = $('#m-revision').val();
        const delivery = $('#m-delivery').val();

        if (title && price) {
            const cardContent = `
                <div class="flex justify-between items-start mb-4">
                    <h4 class="font-medium text-base-300 text-lg milestone-title">${title}</h4>
                    <div class="relative">
                        <button class="text-gray-400 hover:text-gray-600 -mr-1 milestone-menu-btn">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                        <div class="absolute right-0 top-full mt-1 p-1 w-32 bg-white border border-[#C4C8CE] shadow-lg rounded-lg py-1 hidden milestone-menu z-10">
                            <button class="w-full text-left rounded-md px-4 py-2 text-sm text-base-400 hover:bg-base-200/20 hover:text-red-500 delete-milestone">Delete</button>
                            <button class="w-full text-left rounded-md px-4 py-2 text-sm text-base-400 hover:bg-base-200/20 edit-milestone">Edit</button>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 rounded-lg">
                    <div class="bg-[#F6F7F9] p-4 rounded-lg">
                        <p class="text-xs text-base-400 mb-2">Price</p>
                        <p class="font-medium text-base-300">$<span class="milestone-price">${price}</span></p>
                    </div>
                    <div class="bg-[#F6F7F9] p-4 rounded-lg">
                        <p class="text-xs text-base-400 mb-2">Delivery Time</p>
                        <p class="font-medium text-base-300"><span class="milestone-delivery">${delivery}</span></p>
                    </div>
                    <div class="bg-[#F6F7F9] p-4 rounded-lg">
                        <p class="text-xs text-base-400 mb-2">Revision</p>
                        <p class="font-medium text-base-300"><span class="milestone-revision">${revision}</span></p>
                    </div>
                </div>
            `;

            if (editingCard) {
                // Update existing card
                editingCard.html(cardContent);
                editingCard.attr('data-desc', desc);
            } else {
                // Create new card
                const newCard = $(`
                    <div class="border border-gray-200 rounded-lg p-5 bg-white relative group" data-desc="${desc}">
                        ${cardContent}
                    </div>
                `);
                $('#milestone-list').append(newCard);
            }

            // Close modal
            $('#milestone-modal').addClass('hidden');
        }
    });

    // Edit Milestone
    $(document).on('click', '.edit-milestone', function () {
        editingCard = $(this).closest('.group');

        const title = editingCard.find('.milestone-title').text();
        const price = editingCard.find('.milestone-price').text();
        const delivery = editingCard.find('.milestone-delivery').text();
        const revision = editingCard.find('.milestone-revision').text();
        const desc = editingCard.attr('data-desc') || '';

        $('#m-title').val(title);
        $('#m-price').val(price);
        $('#m-delivery').val(delivery);
        $('#m-revision').val(revision);
        $('#m-desc').val(desc);

        $('#milestone-modal h3').text('Edit Milestone');
        $('#save-milestone-btn').text('Update');
        $('#milestone-modal').removeClass('hidden');
    });

    // Toggle Menu
    $(document).on('click', '.milestone-menu-btn', function (e) {
        e.stopPropagation();
        $('.milestone-menu').not($(this).next()).addClass('hidden');
        $(this).next('.milestone-menu').toggleClass('hidden');
    });

    // Close menu when clicking outside
    $(document).on('click', function () {
        $('.milestone-menu').addClass('hidden');
    });

    // Delete Milestone
    $(document).on('click', '.delete-milestone', function () {
        $(this).closest('.group').remove();
    });


    // Payment Option Selection
    const paymentOptionChildren = $('.payment-options').children();
    paymentOptionChildren.click(function () {
        // Remove border-primary from ALL children first
        paymentOptionChildren.removeClass('border-secondary border-2');

        // Add border-primary only to the clicked element
        $(this).addClass('border-secondary border-2 ');
    });
});




