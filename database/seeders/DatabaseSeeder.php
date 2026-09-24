<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@bharatsamachar.com'],
            [
                'name' => 'मुख्य संपादक (Chief Editor)',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'role' => 'admin',
                'avatar' => null,
            ]
        );

        // 2. Categories
        $categoriesData = [
            ['name' => 'राजनीति', 'slug' => 'politics', 'color' => '#dc2626', 'order' => 1, 'is_featured' => true, 'description' => 'देश और विदेश की राजनीति से जुड़ी हर ताज़ा और सटीक खबर।'],
            ['name' => 'राष्ट्रीय', 'slug' => 'national', 'color' => '#ea580c', 'order' => 2, 'is_featured' => true, 'description' => 'देश के कोने-कोने से जुड़ी प्रमुख घटनाएं और ताज़ा समाचार।'],
            ['name' => 'खेल', 'slug' => 'sports', 'color' => '#16a34a', 'order' => 3, 'is_featured' => true, 'description' => 'क्रिकेट, फुटबॉल, ओलंपिक और अन्य खेलों के रोमांचक अपडेट्स।'],
            ['name' => 'मनोरंजन', 'slug' => 'entertainment', 'color' => '#9333ea', 'order' => 4, 'is_featured' => true, 'description' => 'बॉलीवुड, हॉलीवुड, ओटीटी और सेलिब्रिटी गपशप।'],
            ['name' => 'व्यापार', 'slug' => 'business', 'color' => '#2563eb', 'order' => 5, 'is_featured' => true, 'description' => 'शेयर बाजार, अर्थव्यवस्था, बजट और कॉर्पोरेट जगत के समाचार।'],
            ['name' => 'टेक & गैजेट्स', 'slug' => 'tech', 'color' => '#0891b2', 'order' => 6, 'is_featured' => true, 'description' => 'स्मार्टफोन, AI, विज्ञान और तकनीकी दुनिया की नई खोजें।'],
            ['name' => 'दुनिया', 'slug' => 'world', 'color' => '#4f46e5', 'order' => 7, 'is_featured' => true, 'description' => 'अंतरराष्ट्रीय मंच और वैश्विक मामलों पर खास रिपोर्ट।'],
            ['name' => 'राज्य', 'slug' => 'states', 'color' => '#d97706', 'order' => 8, 'is_featured' => true, 'description' => 'उत्तर प्रदेश, बिहार, मध्य प्रदेश, राजस्थान समेत सभी राज्यों की खबरें।'],
        ];

        $categories = [];
        foreach ($categoriesData as $cat) {
            $categories[$cat['slug']] = Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 3. Tags
        $tagsData = [
            'नरेंद्र मोदी', 'राहुल गांधी', 'ISRO', 'चंद्रयान-4', 'शेयर बाजार', 'सेंसेक्स',
            'टीम इंडिया', 'T20 वर्ल्ड कप', 'बॉलीवुड', 'AI टेक्नोलॉजी', 'संसद सत्र', 'मानसून 2026',
            'ईवी गाड़ियां', 'सुप्रीम कोर्ट', 'अर्थव्यवस्था'
        ];

        $tags = [];
        foreach ($tagsData as $tagName) {
            $tags[$tagName] = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName, '-', 'hi') ?: Str::random(8)],
                ['name' => $tagName]
            );
        }

        // 4. Sample News Posts
        $postsData = [
            [
                'title' => 'प्रधानमंत्री मोदी ने नई दिल्ली में वैश्विक नेताओं के साथ की अहम बैठक, रक्षा और व्यापार पर बनी सहमति',
                'summary' => 'नई दिल्ली में आयोजित उच्चस्तरीय द्विपक्षीय वार्ता में प्रधानमंत्री नरेंद्र मोदी ने विभिन्न देशों के शीर्ष नेताओं के साथ रणनीतिक साझेदारी को विस्तार देने पर चर्चा की।',
                'category_id' => $categories['politics']->id,
                'featured_image' => 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'नई दिल्ली में द्विपक्षीय वार्ता के दौरान प्रधानमंत्री नरेंद्र मोदी (फाइल फोटो)',
                'is_breaking' => true,
                'is_featured' => true,
                'is_trending' => true,
                'is_editor_pick' => true,
                'views_count' => 14520,
                'tags' => ['नरेंद्र मोदी', 'संसद सत्र', 'अर्थव्यवस्था'],
                'content' => '<p>नई दिल्ली में आयोजित उच्चस्तरीय द्विपक्षीय वार्ता में प्रधानमंत्री नरेंद्र मोदी ने विभिन्न देशों के शीर्ष नेताओं के साथ रणनीतिक साझेदारी को विस्तार देने पर चर्चा की। बैठक में रक्षा सहयोग, नवीकरणीय ऊर्जा, साइबर सुरक्षा और व्यापारिक रिश्तों को मजबूत करने के लिए कई अहम समझौतों पर हस्ताक्षर किए गए।</p>
<p>प्रधानमंत्री ने अपने संबोधन में कहा कि भारत विश्व बंधु के रूप में वैश्विक शांति और विकास के लिए निरंतर प्रयासरत है। आने वाले वर्षों में भारत और उसके सहयोगी देश तकनीकी हस्तांतरण और डिजिटल पब्लिक इन्फ्रास्ट्रक्चर के क्षेत्र में मील का पत्थर साबित होंगे।</p>
<h3>रणनीतिक साझेदारी को नई दिशा</h3>
<p>बैठक में उपस्थित प्रतिनिधियों ने भारत की आर्थिक प्रगति और वैश्विक मंच पर इसके नेतृत्वकारी भूमिका की सराहना की। इस दौरान हिंद-प्रशांत क्षेत्र में स्थिरता और स्वतंत्र व्यापार गलियारों को सुरक्षित रखने पर भी विशेष सहमति बनी।</p>',
            ],
            [
                'title' => 'ISRO के चंद्रयान-4 मिशन को कैबिनेट से हरी झंडी, चांद से नमूने वापस लाने की ऐतिहासिक तैयारी',
                'summary' => 'भारतीय अंतरिक्ष अनुसंधान संगठन (ISRO) ने एक और ऐतिहासिक छलांग लगाते हुए चंद्रयान-4 मिशन का खाका तैयार कर लिया है।',
                'category_id' => $categories['tech']->id,
                'featured_image' => 'https://images.unsplash.com/photo-1614728894747-a83421e2b9c9?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'इसरो का बहुप्रतीक्षित मून मिशन चंद्रयान-4',
                'is_breaking' => true,
                'is_featured' => true,
                'is_trending' => true,
                'is_editor_pick' => false,
                'views_count' => 28940,
                'tags' => ['ISRO', 'चंद्रयान-4', 'AI टेक्नोलॉजी'],
                'content' => '<p>भारतीय अंतरिक्ष अनुसंधान संगठन (ISRO) चंद्रयान-3 की ऐतिहासिक सफलता के बाद अब चंद्रयान-4 की तैयारियों में जुट गया है। केंद्रीय मंत्रिमंडल ने इस महत्वाकांक्षी मिशन के लिए बजट को मंज़ूरी दे दी है।</p>
<p>इस मिशन का मुख्य उद्देश्य चंद्रमा के दक्षिणी ध्रुव पर लैंडिंग करके वहां से मिट्टी और चट्टानों के नमूने (Lunar Samples) इकट्ठा कर सुरक्षित रूप से पृथ्वी पर वापस लाना है।</p>
<p>इसरो प्रमुख ने बताया कि इस मिशन में उन्नत स्वायत्त डॉकिंग प्रणाली और अत्याधुनिक रोवर का इस्तेमाल किया जाएगा, जो पूरी तरह स्वदेशी तकनीक पर आधारित होगा।</p>',
            ],
            [
                'title' => 'शेयर बाजार में इतिहास रचा: सेंसेक्स 85,000 के पार, विदेशी निवेशकों ने झोंके अरबों रुपये',
                'summary' => 'भारतीय शेयर बाजार ने आज तेजी का नया कीर्तिमान स्थापित किया। बैंकिंग और आईटी शेयरों में जबरदस्त खरीदारी से बाजार में चौतरफा रौनक देखी गई।',
                'category_id' => $categories['business']->id,
                'featured_image' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'दलाल स्ट्रीट पर लगातार बढ़त जारी',
                'is_breaking' => false,
                'is_featured' => true,
                'is_trending' => true,
                'is_editor_pick' => true,
                'views_count' => 19300,
                'tags' => ['शेयर बाजार', 'सेंसेक्स', 'अर्थव्यवस्था'],
                'content' => '<p>भारतीय इक्विटी बेंचमार्क इंडेक्स बीएसई सेंसेक्स और एनएसई निफ्टी ने आज कारोबार के दौरान नया लाइफ-टाइम हाई बनाया। मजबूत वैश्विक संकेतों और खुदरा निवेशकों की बढ़ती भागीदारी ने तेजी को गति दी।</p>
<p>विशेषज्ञों का मानना है कि भारत की मजबूत जीडीपी ग्रोथ और कंपनियों के उत्साहजनक तिमाही नतीजों के चलते विदेशी पोर्टफोलियो निवेशक (FPIs) लगातार भारतीय बाजारों में निवेश कर रहे हैं।</p>',
            ],
            [
                'title' => 'टी20 सीरीज में टीम इंडिया का दबदबा: आखिरी ओवर के रोमांच में ऑस्ट्रेलिया को 6 रन से हराया',
                'summary' => 'भारतीय टीम ने शानदार खेल का प्रदर्शन करते हुए पांच मैचों की टी20 श्रृंखला 4-1 से अपने नाम कर ली। युवा खिलाड़ियों का प्रदर्शन लाजवाब रहा।',
                'category_id' => $categories['sports']->id,
                'featured_image' => 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'जीत के बाद भारतीय खिलाड़ियों का जश्न',
                'is_breaking' => false,
                'is_featured' => true,
                'is_trending' => true,
                'is_editor_pick' => false,
                'views_count' => 32150,
                'tags' => ['टीम इंडिया', 'T20 वर्ल्ड कप'],
                'content' => '<p>मेलबर्न क्रिकेट ग्राउंड पर खेले गए अंतिम रोमांचक मुकाबले में भारतीय टीम ने अंतिम ओवर में शानदार गेंदबाजी के दम पर जीत दर्ज की।</p>
<p>पहले बल्लेबाजी करते हुए भारत ने 20 ओवर में 198 रन बनाए, जिसके जवाब में विपक्षी टीम 192 रन ही बना सकी। प्लेयर ऑफ द मैच का खिताब तेज गेंदबाज को उनके 4 विकेट के स्पेल के लिए दिया गया।</p>',
            ],
            [
                'title' => 'बॉलीवुड का नया धमाका: एक्शन थ्रिलर फिल्म ने पहले ही दिन बॉक्स ऑफिस पर कमाए 75 करोड़',
                'summary' => 'सिनेमाघरों में दर्शकों की भारी भीड़, एडवांस बुकिंग में टूटे सारे पुराने रिकॉर्ड। समीक्षकों ने भी फिल्म को दिए 4.5 स्टार।',
                'category_id' => $categories['entertainment']->id,
                'featured_image' => 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'सिनेमाघरों के बाहर भारी उत्साह',
                'is_breaking' => false,
                'is_featured' => false,
                'is_trending' => true,
                'is_editor_pick' => false,
                'views_count' => 12400,
                'tags' => ['बॉलीवुड'],
                'content' => '<p>सिनेमा प्रेमियों के लिए यह हफ्ता बेहद खास रहा। बहुप्रतीक्षित मेगा बजट एक्शन फिल्म ने रिलीज होते ही बॉक्स ऑफिस पर तहलका मचा दिया है। देश भर के सिंगल स्क्रीन और मल्टीप्लेक्स में हाउसफुल के बोर्ड लगे दिखे।</p>',
            ],
            [
                'title' => 'आर्टिफिशियल इंटेलिजेंस (AI) से स्वास्थ्य क्षेत्र में क्रांति: नई तकनीक से चंद सेकंड में होगी बीमारियों की पहचान',
                'summary' => 'भारतीय वैज्ञानिकों और शोधकर्ताओं ने विकसित किया डीप लर्निंग मॉडल जो एक्स-रे और एमआरआई रिपोर्ट का सटीक विश्लेषण करने में सक्षम है।',
                'category_id' => $categories['tech']->id,
                'featured_image' => 'https://images.unsplash.com/photo-1677442136019-21780ecad995?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'स्वास्थ्य क्षेत्र में AI का बढ़ता प्रभाव',
                'is_breaking' => false,
                'is_featured' => false,
                'is_trending' => false,
                'is_editor_pick' => true,
                'views_count' => 8700,
                'tags' => ['AI टेक्नोलॉजी'],
                'content' => '<p>तकनीकी विकास के इस दौर में कृत्रिम बुद्धिमत्ता (AI) ने चिकित्सा विज्ञान में नए आयाम स्थापित किए हैं। अब ग्रामीण इलाकों में भी दूरदराज के मरीजों को विशेषज्ञ स्तर की जांच रिपोर्ट त्वरित गति से मिल सकेगी।</p>',
            ],
            [
                'title' => 'सुप्रीम कोर्ट का ऐतिहासिक फैसला: पर्यावरण संरक्षण और बुनियादी विकास के बीच संतुलन पर कड़े दिशा-निर्देश',
                'summary' => 'शीर्ष अदालत ने सभी राज्यों को हरित पट्टी संरक्षित करने और अवैध निर्माण पर सख्त रोक लगाने के निर्देश जारी किए।',
                'category_id' => $categories['national']->id,
                'featured_image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'सर्वोच्च न्यायालय, नई दिल्ली',
                'is_breaking' => false,
                'is_featured' => false,
                'is_trending' => false,
                'is_editor_pick' => true,
                'views_count' => 9600,
                'tags' => ['सुप्रीम कोर्ट', 'संसद सत्र'],
                'content' => '<p>सर्वोच्च न्यायालय की संवैधानिक पीठ ने विकास परियोजनाओं के पर्यावरणीय प्रभाव आकलन को लेकर बेहद अहम फैसला सुनाया है। अदालत ने कहा कि विकास आवश्यक है परंतु पर्यावरण की कीमत पर नहीं।</p>',
            ],
            [
                'title' => 'उत्तर प्रदेश में आधुनिक एक्सप्रेसवे नेटवर्क का विस्तार, पूर्वांचल और बुंदेलखंड को मिला नया औद्योगिक गलियारा',
                'summary' => 'राज्य में बुनियादी ढांचे के विकास से लाखों नए रोजगार के अवसर सृजित होने की उम्मीद है।',
                'category_id' => $categories['states']->id,
                'featured_image' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'आधुनिक 6-लेन एक्सप्रेसवे कॉरिडोर',
                'is_breaking' => false,
                'is_featured' => false,
                'is_trending' => false,
                'is_editor_pick' => false,
                'views_count' => 6400,
                'tags' => ['अर्थव्यवस्था'],
                'content' => '<p>उत्तर प्रदेश सरकार ने कनेक्टिविटी और लॉजिस्टिक्स को बढ़ावा देने के लिए नए औद्योगिक गलियारों को हरी झंडी दे दी है। इसके तहत प्रमुख शहरों के बीच यात्रा का समय घटकर आधा रह जाएगा।</p>',
            ],
            [
                'title' => 'संसद का मानसून सत्र: कई महत्वपूर्ण विधेयकों पर चर्चा, विपक्ष और सत्तापक्ष के बीच तीखी बहस',
                'summary' => 'संसद में आज जनहित से जुड़े कई अहम बिल पेश किए गए। दोनों सदनों में विभिन्न राष्ट्रीय मुद्दों पर जोरदार चर्चा हुई।',
                'category_id' => $categories['politics']->id,
                'featured_image' => 'https://images.unsplash.com/photo-1590402494682-cd3fb53b1f70?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'संसद भवन का दृश्य',
                'is_breaking' => true,
                'is_featured' => false,
                'is_trending' => true,
                'is_editor_pick' => false,
                'views_count' => 11200,
                'tags' => ['संसद सत्र', 'राहुल गांधी', 'नरेंद्र मोदी'],
                'content' => '<p>संसद के मानसून सत्र के दौरान आज विभिन्न विधायी कार्यों को निपटाया गया। वित्त और गृह मामलों से जुड़े विधेयकों पर विभिन्न दलों के सांसदों ने अपने सुझाव रखे।</p>',
            ],
            [
                'title' => 'इलेक्ट्रिक वाहनों (EV) की बिक्री में रिकॉर्ड 45% उछाल, बैटरी स्वैपिंग इंफ्रास्ट्रक्चर का विस्तार तेज',
                'summary' => 'देश भर में चार्जिंग स्टेशनों की संख्या में तेजी से बढ़ोतरी और सरकारी सब्सिडी से ग्राहकों का रुझान ईवी की तरफ बढ़ा।',
                'category_id' => $categories['business']->id,
                'featured_image' => 'https://images.unsplash.com/photo-1563720223185-11003d516935?w=1200&auto=format&fit=crop&q=80',
                'image_caption' => 'इलेक्ट्रिक कार चार्जिंग स्टेशन',
                'is_breaking' => false,
                'is_featured' => false,
                'is_trending' => false,
                'is_editor_pick' => false,
                'views_count' => 7800,
                'tags' => ['ईवी गाड़ियां', 'अर्थव्यवस्था'],
                'content' => '<p>हरित गतिशीलता की ओर बढ़ते भारत में इलेक्ट्रिक दोपहिया और चार पहिया वाहनों की मांग लगातार बढ़ रही है। ऑटोमोबाइल निर्माताओं ने नए किफायती मॉडल्स बाजार में उतारे हैं।</p>',
            ],
        ];

        foreach ($postsData as $index => $pData) {
            $postTags = $pData['tags'] ?? [];
            unset($pData['tags']);

            $post = Post::create(array_merge($pData, [
                'slug' => Str::slug($pData['title'], '-', 'hi') ?: 'news-article-' . ($index + 1),
                'user_id' => $admin->id,
                'status' => 'published',
                'published_at' => now()->subHours(rand(1, 48)),
                'meta_title' => $pData['title'],
                'meta_description' => $pData['summary'],
                'meta_keywords' => implode(', ', $postTags),
            ]));

            // Attach tags
            $tagIds = [];
            foreach ($postTags as $tName) {
                if (isset($tags[$tName])) {
                    $tagIds[] = $tags[$tName]->id;
                }
            }
            if (!empty($tagIds)) {
                $post->tags()->sync($tagIds);
            }
        }

        // 5. Advertisements
        $adsData = [
            [
                'title' => 'हेडर टॉप लीडरबोर्ड बैनर (Header Banner 728x90)',
                'placement' => 'header_banner',
                'type' => 'image',
                'image_path' => 'https://picsum.photos/728/90?random=101',
                'target_url' => 'https://bharatsamachar.com',
                'is_active' => true,
            ],
            [
                'title' => 'होमपेज मिडिल बैनर (Home Middle 970x250)',
                'placement' => 'home_middle',
                'type' => 'image',
                'image_path' => 'https://picsum.photos/970/120?random=102',
                'target_url' => 'https://bharatsamachar.com',
                'is_active' => true,
            ],
            [
                'title' => 'साइडबार टॉप स्क्वायर ऐड (Sidebar Top 300x250)',
                'placement' => 'sidebar_top',
                'type' => 'image',
                'image_path' => 'https://picsum.photos/300/250?random=103',
                'target_url' => 'https://bharatsamachar.com',
                'is_active' => true,
            ],
            [
                'title' => 'साइडबार बॉटम ऐड (Sidebar Bottom 300x600)',
                'placement' => 'sidebar_bottom',
                'type' => 'image',
                'image_path' => 'https://picsum.photos/300/400?random=104',
                'target_url' => 'https://bharatsamachar.com',
                'is_active' => true,
            ],
            [
                'title' => 'डिटेल पेज आर्टिकल के बीच विज्ञापन (Detail Content Ad)',
                'placement' => 'detail_top',
                'type' => 'image',
                'image_path' => 'https://picsum.photos/728/120?random=105',
                'target_url' => 'https://bharatsamachar.com',
                'is_active' => true,
            ],
            [
                'title' => 'कैटेगरी साइडबार विज्ञापन (Category Sidebar)',
                'placement' => 'category_sidebar',
                'type' => 'image',
                'image_path' => 'https://picsum.photos/300/250?random=106',
                'target_url' => 'https://bharatsamachar.com',
                'is_active' => true,
            ],
        ];

        foreach ($adsData as $ad) {
            Ad::create($ad);
        }

        // 6. Settings
        $settingsData = [
            'site_name' => 'भारत समाचार',
            'site_tagline' => 'Bharat Samachar · Sach Ki Awaaz',
            'site_description' => 'भारत समाचार - देश की सबसे तेज़ हिंदी न्यूज़ वेबसाइट। ताज़ा खबरें, ब्रेकिंग न्यूज़, राजनीति, खेल, मनोरंजन और अंतरराष्ट्रीय समाचार।',
            'contact_email' => 'contact@bharatsamachar.com',
            'contact_phone' => '+91 11 2345 6789',
            'contact_address' => 'प्रेस एन्क्लेव, नई दिल्ली - 110001',
            'social_facebook' => 'https://facebook.com',
            'social_twitter' => 'https://x.com',
            'social_youtube' => 'https://youtube.com',
            'social_instagram' => 'https://instagram.com',
            'social_telegram' => 'https://telegram.org',
            'social_whatsapp' => 'https://whatsapp.com',
            'epaper_url' => '#',
            'ticker_speed' => '30',
            'footer_about' => 'भारत समाचार देश का अग्रणी और विश्वसनीय हिंदी समाचार पोर्टल है। हम राजनीति, राष्ट्रीय, अंतरराष्ट्रीय, खेल, व्यापार और मनोरंजन जगत की ताज़ा और निष्पक्ष खबरें 24x7 आप तक पहुंचाते हैं।',
            'copyright_text' => '© 2026 भारत समाचार (Bharat Samachar). सर्वाधिकार सुरक्षित।',
        ];

        foreach ($settingsData as $key => $val) {
            Setting::set($key, $val);
        }
    }
}
