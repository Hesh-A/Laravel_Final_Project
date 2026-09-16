<?php

use App\CollaborationStatus;

it('returns the correct label for each collaboration status', function () {
    expect(CollaborationStatus::PENDING->label())->toBe('Pending');
    expect(CollaborationStatus::APPROVED->label())->toBe('Approved');
    expect(CollaborationStatus::REJECTED->label())->toBe('Rejected');
});
