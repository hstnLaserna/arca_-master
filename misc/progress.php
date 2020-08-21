<?php echo hello;

[X} Lookup of senior in database through textboxes:
[X}     OSCA ID
[X}     Firstname
[X}     Midlename
[X}     Lastname


[X} Update Address of senior citizen
[X} Add Address of senior citizen


[X} Admin profile
[X}    Show card of admin
[X}    View profile 
[X}    Edit profile


[X} Pharmacy
[X} LRT
[X} Restaurant

[X} Populate (x)transactions and company with faux data

tables:
    company
        business_type (add)     (varchar 120)

    transaction
        id    (int 20)
        trans_date  (timestamp)
        company_id    (int 20)
        member_id    (int 20)
        clerk   (varchar 120)


    pharmacy
        id    (int 20)
        transaction_id    (int 20)
        drug_id    (int 20)
        quantity    (int 20)
        unit_price    (decimal 13,2)
        vat_exempt_price    (decimal 13,2)
        discounted_price    (decimal 13,2)
        payable_price   (varchar 120)

    drug
        id    (int 20)
        generic_name    (varchar 120)
        brand    (varchar 120)
        dose    (int 20)
        unit    (varchar 120)


    transportation
        id    (int 20)
        transaction_id    (int 20)
        desc    (varchar 120)
        discounted_price    (decimal 13,2)
        payable_price    (decimal 13,2)


    retaurant
        id    (int 20)
        transaction_id    (int 20)
        desc    (varchar 120)
        vat_exempt_price    (decimal 13,2)
        discounted_price    (decimal 13,2)
        payable_price    (decimal 13,2)

    guardian
        id (int 20)
        first_name  (varchar 120)
        middle_name (varchar 120)
        last_name   (varchar 120)
        sex (varchar 120)
        relationship    (varchar 120)
        contact_number (varchar 20)
        email   (varchar 120)
        member_id   (int 20)
    
    ALTER TABLE: `address`
        -- REMOVED FK and COLUMN `member_id`
    
    address_jt
        id  (int 20) NN
        address_id (int 20) NN
        member_id (int 20)
        company_id (int 20)
        guardian_id (int 20)






[x] home
    [ ??? ] rearrange of dashboard and navbar


[X} search pagination
[] members
    // [XXXX] landing page
    [x] registration
    [x] master list
        *members
            [x] query of display all transaction
            [x] query of display pharmacy transaction
            [x] query of display food transaction
            [x] query of display transportation transaction
            [] contact details
            [] guardian
                [] table
                [] create
                [] read
                [] update
                [] delete

[] administration
    [x] user profile
    [] access of user and admin levels
    [x] registration of new admin

[] Validate inputs alphanumeric, valid symbols only

[x] address table and address junction table

[] address select city from province

[] change implementation of forgot password

[] phone number and email validation in javascript and php

[] Picture upload

[] terminal





eto para sakin,
// With regards to the ID
// Current System 
    Current OSCA IDs are non-standard kasi depende sa municipality yung design and paga-administer ng OSCA Membership. Whenever magta-transact, hahanapin pa yung ID sa bag or wallet, na minsan di rin nila dala. Dagdag patagal sa transaction.
// Proposed system
    Aim is to eliminate the fact na nawawala or nakakalimutan ng senior yung ID nila. With the proposed digital ID, magiging faster ang transaction, 1 tap then yung transactions mase-send na sa system and even validation ng pinamili (in cases of Pharmaceuticals). 
// PS
Stated din sa AO 2012-0007 din na for any Senior Citizen to claim VAT Exempt and discounts, any of the following will suffice:
    • Government issued certificate or;
    • Any Valid ID can qualify them as Senior Citizen
// Pang huling banat pag di pa rin convinced
The OSCA can still issue physical IDs for the senior citizens. Pero to avail discounts  and keep record of such transactions, digitization of the records is the best way to simplify the life of the Senior Citizen.


//  Sa Contents ng NFC tag
//  Idea
    Maglagay ng senior citizen name, guardian’s contact details.
//  Pros
    Makokontact yung guardians kung halimbawa mag collapse yung senior, or any case of emergency. 
// Cons
    Possibility of hack ng device, example madikitan sya ng hacker with short range reader, pwede magamit maliciously ung details ng guardian at nung senior citizen.
// Advantage: 
    If walang details ng guardian’s contact, baka maeliminate kasi yung usability ng physical OSCA ID

    Pano natin ide-defend? Hahaha