-- Add sample articles (simpler version to avoid SQL syntax errors)
-- Run this after 002_seed.sql

-- Clear existing sample posts except the first one
DELETE FROM posts WHERE id > 1;

-- Add 8 engaging articles with proper escaping
INSERT INTO posts (slug, title, excerpt, content, cover_image, category_id, author_id, estimated_read_minutes, published_at) VALUES

-- Faith Article 1
('when-prayer-feels-like-silence',
'When Prayer Feels Like Silence',
'Ever felt like your prayers are hitting the ceiling? What I learned when God seemed quietest.',
'<p><strong>Scripture:</strong> "Be still, and know that I am God." — Psalm 46:10</p><p>I used to think unanswered prayers meant God was not listening. Turns out, sometimes the answer is: Wait. Watch. Learn what I am doing in the silence.</p><h2>The Uncomfortable Gift of Waiting</h2><p>Three months. That is how long I prayed for a breakthrough that never came—at least not in the way I expected. My journal entries from that season read like a spiritual wrestling match.</p><p>But here is what the waiting taught me: God silence is not absence. It is often His loudest teaching tool.</p><h2>What I Discovered</h2><p>During those months, I learned to distinguish between what I wanted and what I actually needed. The breakthrough I was begging for? Looking back, I was not ready for it. God was building character I would need later.</p><p>Keep praying, even when it feels like talking to the ceiling. That silence might be God saying: Trust Me enough to wait for something better.</p>',
'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=1200&q=80',
(SELECT id FROM categories WHERE slug='faith' LIMIT 1),
(SELECT id FROM users LIMIT 1),
7,
DATE_SUB(NOW(), INTERVAL 2 DAY)),

-- Growth Article 1
('the-art-of-becoming',
'The Art of Becoming: Why Growth Feels Uncomfortable',
'Growth is not always glamorous. Sometimes it is messy, painful, and makes you want to quit.',
'<p>Let us be honest: nobody posts their becoming season on Instagram. We show the butterfly, not the cocoon. The harvest, not the planting.</p><p>But what if the messy middle is actually where the magic happens?</p><h2>The Myth of Comfortable Growth</h2><p>I used to believe that spiritual growth should feel peaceful. Reality check: Growth rarely feels peaceful while it is happening.</p><h2>Signs You Are Actually Growing</h2><ul><li>You are uncomfortable - if everything feels too easy, you might be coasting</li><li>Old patterns do not fit anymore</li><li>Your prayers are changing - asking for character, not just comfort</li><li>People notice - even when you do not see it</li></ul><p>Keep going. Keep showing up. Keep choosing growth even when it is hard.</p>',
'https://images.unsplash.com/photo-1499209974431-9dddcece7f88?auto=format&fit=crop&w=1200&q=80',
(SELECT id FROM categories WHERE slug='growth' LIMIT 1),
(SELECT id FROM users LIMIT 1),
6,
DATE_SUB(NOW(), INTERVAL 5 DAY)),

-- Womanhood Article 1
('embracing-strength-and-softness',
'Embracing Strength and Softness',
'You do not have to choose between being strong and being soft. What if you could be both?',
'<p>For years, I thought I had to pick a side. Be the strong woman or be the soft woman.</p><p>Plot twist: I can be both. And so can you.</p><h2>The Lie We Have Been Sold</h2><p>Society loves to put women in boxes. But look at the women in Scripture: Deborah led armies and judged Israel. Esther displayed both courage and strategic wisdom.</p><h2>What True Strength Looks Like</h2><p>Real strength is not refusing to cry. It is allowing yourself to feel deeply and still choosing hope.</p><p>Real strength is not doing everything alone. It is knowing when to ask for help.</p><h2>The Power of Softness</h2><p>Our world mistakes softness for weakness. But there is nothing weak about choosing kindness, extending grace, or staying tender in a world trying to make you hard.</p>',
'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=1200&q=80',
(SELECT id FROM categories WHERE slug='womanhood' LIMIT 1),
(SELECT id FROM users LIMIT 1),
6,
DATE_SUB(NOW(), INTERVAL 7 DAY)),

-- Family Article 1
('building-legacy-not-just-family',
'Building a Legacy, Not Just a Family',
'It is not about perfect family photos. It is about raising humans who will change the world.',
'<p><strong>Scripture:</strong> "Train up a child in the way he should go" — Proverbs 22:6</p><p>Last week, I watched my daughter help a friend without being asked. No cameras. No audience. Just kindness in motion.</p><p>And I thought: This is what legacy looks like.</p><h2>Rethinking Family Success</h2><p>We get so caught up in the visible markers of good parenting. But what if true success is measured in character, not credentials?</p><h2>Five Legacy Foundations</h2><p><strong>Faith Over Fear:</strong> Teach them to run TO God in hard times.</p><p><strong>Character Over Comfort:</strong> It is easier to give them everything they want. It is better to teach them to work.</p><p><strong>Truth Over Trend:</strong> Culture will shift. God Word will not.</p><p><strong>Service Over Status:</strong> Show them that true greatness is found in serving others.</p><p><strong>Love Over Legalism:</strong> They need to know they are loved unconditionally.</p>',
'https://images.unsplash.com/photo-1511895426328-dc8714191300?auto=format&fit=crop&w=1200&q=80',
(SELECT id FROM categories WHERE slug='family' LIMIT 1),
(SELECT id FROM users LIMIT 1),
7,
DATE_SUB(NOW(), INTERVAL 10 DAY)),

-- Faith Article 2
('everyday-worship',
'Everyday Worship: Making Monday Sacred',
'What if worship was not just a Sunday thing? What if your laundry and commute could become acts of praise?',
'<p>I used to compartmentalize my faith: Sundays for God, Mondays through Saturdays for everything else. Then I realized — God does not compartmentalize. Why should I?</p><h2>Redefining Worship</h2><p>Worship is not just singing songs. It is any act that says: God, You are worthy of my attention, my effort, my very life.</p><p>Washing dishes? That is worship. Sitting in traffic? Worship. Cooking dinner? Worship. Working your 9-to-5? Worship.</p><h2>Making the Shift</h2><p>Start small. This week, try morning dedication, task transformation, gratitude pauses, and evening reflection.</p><p>Your whole life can become an offering. Every moment, an opportunity to say: God, You are worthy.</p>',
'https://images.unsplash.com/photo-1490730141103-6cac27aaab94?auto=format&fit=crop&w=1200&q=80',
(SELECT id FROM categories WHERE slug='faith' LIMIT 1),
(SELECT id FROM users LIMIT 1),
5,
DATE_SUB(NOW(), INTERVAL 14 DAY)),

-- Growth Article 2
('healing-isnt-linear',
'Healing Is Not Linear',
'Two steps forward, one step back, three steps sideways — and still making progress.',
'<p>If healing was a straight line, we would all be healed by now. But healing is more like a spiral — you revisit the same issues at deeper levels, each time with more tools, more wisdom, more grace.</p><h2>The Myth of Being Over It</h2><p>We love to declare victory. Then something triggers the old wound and we feel like failures.</p><p>But here is truth: Having a bad day does not erase your progress. Feeling the pain again does not mean you are back at square one.</p><h2>What Real Healing Looks Like</h2><ul><li>You recognize triggers faster</li><li>You respond with wisdom, not just reaction</li><li>You give yourself grace on hard days</li><li>You celebrate small victories</li></ul><p>Keep going. Keep healing. The zigzag is part of the journey.</p>',
'https://images.unsplash.com/photo-1517677129300-07b130802f46?auto=format&fit=crop&w=1200&q=80',
(SELECT id FROM categories WHERE slug='growth' LIMIT 1),
(SELECT id FROM users LIMIT 1),
6,
DATE_SUB(NOW(), INTERVAL 17 DAY)),

-- Womanhood Article 2
('escaping-comparison',
'Escaping the Comparison Game',
'She is more successful. She has it all together. But her highlight reel is not your behind-the-scenes.',
'<p>Three years ago, I almost quit blogging because someone else platform was growing faster. I felt invisible, inadequate, behind.</p><p>Then God whispered: I gave you your race. Why are you trying to run hers?</p><h2>The Thief Called Comparison</h2><p>Comparison will rob you blind if you let it. It steals your joy, your confidence, your peace, and your progress.</p><h2>The Social Media Trap</h2><p>Instagram shows the victory, not the valley. You are comparing your chapter 3 to her chapter 20. Stop it.</p><h2>Finding Freedom</h2><p>Unfollow strategically. Celebrate others genuinely. Focus on your lane. Track your own progress.</p><p>Your story is uniquely yours. Live it fully and comparison-free.</p>',
'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=1200&q=80',
(SELECT id FROM categories WHERE slug='womanhood' LIMIT 1),
(SELECT id FROM users LIMIT 1),
5,
DATE_SUB(NOW(), INTERVAL 20 DAY)),

-- Family Article 2
('imperfect-family-perfect-love',
'Imperfect Family, Perfect Love',
'Your family does not have to be perfect to be good. Sometimes messy and chaotic IS the blessing.',
'<p>Last night, dinner was burnt, homework was not done, and we all went to bed without resolving an argument. And you know what? We are still a good family.</p><h2>The Perfect Family Myth</h2><p>Social media sells perfection: matching outfits, peaceful breakfasts, kids who never fight. Real life? It is messier. And that is okay.</p><h2>What Actually Matters</h2><p>Showing up even on hard days. Saying sorry. Laughing together. Praying through problems.</p><h2>Grace for the Hard Days</h2><p>Some days you will mess up. You will yell when you meant to talk calmly. That is when grace shows up.</p><p>Your family does not need perfect. They need presence, patience, and pizza nights where everyone talks.</p>',
'https://images.unsplash.com/photo-1609220136736-443140cffec6?auto=format&fit=crop&w=1200&q=80',
(SELECT id FROM categories WHERE slug='family' LIMIT 1),
(SELECT id FROM users LIMIT 1),
5,
DATE_SUB(NOW(), INTERVAL 24 DAY));
