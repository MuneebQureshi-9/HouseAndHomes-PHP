<?php
declare(strict_types=1);

$navLinks = [
    ['label' => 'Buy', 'href' => '/buyers/'],
    ['label' => 'Sell', 'href' => '/sellers/'],
    ['label' => 'Listings', 'href' => '/listings/'],
    ['label' => 'Pre-Construction', 'href' => '/pre-construction/'],
    [
        'label' => 'Neighbourhoods',
        'href' => '/neighbourhoods/',
        'children' => [
            ['label' => 'Toronto', 'href' => '/neighbourhoods/toronto/'],
            ['label' => 'Mississauga', 'href' => '/neighbourhoods/mississauga/'],
            ['label' => 'Brampton', 'href' => '/neighbourhoods/brampton/'],
            ['label' => 'Oakville', 'href' => '/neighbourhoods/oakville/'],
            ['label' => 'Milton', 'href' => '/neighbourhoods/milton/'],
            ['label' => 'GTA', 'href' => '/neighbourhoods/gta/'],
        ],
    ],
    ['label' => 'About', 'href' => '/about/'],
    ['label' => 'Blog', 'href' => '/blog/'],
    ['label' => 'Vlogs', 'href' => '/vlogs/'],
];

$footerQuickLinks = [
    ['label' => 'Buy', 'href' => '/buyers/'],
    ['label' => 'Sell', 'href' => '/sellers/'],
    ['label' => 'Listings', 'href' => '/listings/'],
    ['label' => 'Pre-Construction', 'href' => '/pre-construction/'],
    ['label' => 'Free Valuation', 'href' => '/free-market-evaluation/'],
];

$footerCompanyLinks = [
    ['label' => 'About MAK', 'href' => '/about/'],
    ['label' => 'Services', 'href' => '/services/'],
    ['label' => 'FAQ', 'href' => '/faq/'],
    ['label' => 'Testimonials', 'href' => '/testimonials/'],
    ['label' => 'Blog', 'href' => '/blog/'],
    ['label' => 'Vlogs', 'href' => '/vlogs/'],
    ['label' => 'Contact', 'href' => '/contact/'],
];

$faqSections = [
    'Buying a Home' => [
        [
            'question' => 'How much do I need for a down payment in Toronto?',
            'answer' => 'In Canada, the minimum down payment depends on the purchase price of the home. For homes under $500,000, the minimum is 5%. For homes between $500,000 and $999,999, it\'s 5% of the first $500k and 10% of the remainder. For homes $1 million or more, a strict minimum 20% down payment is required.',
        ],
        [
            'question' => 'What are closing costs and how much should I budget?',
            'answer' => 'Closing costs typically range from 1.5% to 4% of the purchase price. These include land transfer taxes (in Toronto, you pay both Provincial and Municipal land transfer taxes), legal fees, title insurance, and potential appraisal or inspection fees. We always provide our clients with a detailed closing cost estimate before making an offer.',
        ],
        [
            'question' => 'Do I have to pay the real estate agent when buying a home?',
            'answer' => 'In the vast majority of standard real estate transactions in Ontario, the buyer does not pay out-of-pocket commissions to their agent. The seller\'s agent typically splits the total commission (paid by the seller from the proceeds of the sale) with the buyer\'s agent.',
        ],
    ],
    'Selling a Home' => [
        [
            'question' => 'What is the best time of year to sell a house in the GTA?',
            'answer' => 'Traditionally, the Spring (March-May) and Fall (September-November) markets are the most active in the GTA. However, the \"best\" time often depends on your specific property type, neighborhood, and personal timeline. A properly priced and expertly marketed luxury home can sell well in any season.',
        ],
        [
            'question' => 'Should I renovate before listing my home?',
            'answer' => 'Not all renovations yield a positive return on investment. Minor cosmetic updates like fresh paint, professional cleaning, decluttering, and strategic staging are almost always recommended. Before undertaking major renovations like kitchens or bathrooms specifically for a sale, consult with us for a free property evaluation to determine if the cost will actually increase your bottom line.',
        ],
        [
            'question' => 'How does MAK market luxury properties differently?',
            'answer' => 'Our luxury marketing strategy goes far beyond the MLS. We utilize cinematic video tours, drone photography, targeted international digital advertising, print features in luxury publications, and our exclusive private network of high-net-worth buyers and agents to ensure maximum premium exposure.',
        ],
    ],
    'Pre-Construction' => [
        [
            'question' => 'What is the 10-day cooling-off period?',
            'answer' => 'Under the Ontario Condominium Act, buyers of new pre-construction condominiums have a statutory 10-day cooling-off period. During this time, you can have your lawyer review the Agreement of Purchase and Sale and, if you choose, cancel the contract without penalty.',
        ],
        [
            'question' => 'What are interim occupancy fees?',
            'answer' => 'When a pre-construction condo building is finished, you will move in during the \"interim occupancy\" phase before the building is officially registered and you hold the final title. During this period (which can last several months), you pay the developer a monthly fee roughly equivalent to the interest on the unpaid balance of the purchase price, estimated property taxes, and common expenses. This does not go toward your mortgage principal.',
        ],
        [
            'question' => 'Why do I need an agent for pre-construction if I buy directly from the builder?',
            'answer' => 'The sales representatives at the presentation center work for the developer, not you. Having your own agent (at no cost to you) ensures your interests are protected, helps negotiate contract terms like capping development levies or assigning the unit, and provides VIP access to better floor plans and early pricing before the public launch.',
        ],
    ],
    'General' => [
        [
            'question' => 'What areas of the GTA do you serve?',
            'answer' => 'We serve the entire Greater Toronto Area, with specialized expertise in Toronto (Downtown, Midtown, Luxury enclaves), Mississauga, Oakville, Brampton, Vaughan, and Richmond Hill.',
        ],
        [
            'question' => 'How long has Muhammad Arshad Khan been in real estate?',
            'answer' => 'Muhammad is a highly experienced, award-winning broker with a proven track record of facilitating over $1 Billion in real estate transactions throughout his career.',
        ],
    ],
];

function site_url(string $path = '/'): string
{
    return $path;
}
