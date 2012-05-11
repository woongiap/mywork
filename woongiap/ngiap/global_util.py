ANY_STATE='MY-all'
ANY_CATEGORY='999'
g_category_map={
				'180':('Food & Drink', 'k_ft_180'), # put 'food' the first
				'90':('Car & Vehicle', 'k_ft_090'),
				'240':('Housing & Apartment', 'k_ft_240'),
				'300':('Job', 'k_ft_300'),
				'360':('Land & Factory', 'k_ft_360'),
				'480':('Product & Service', 'k_ft_480'),
				'540':('Recreation & Travel', 'k_ft_540'),
				'570':('Shop & Office', 'k_ft_570'),
				ANY_CATEGORY:('-- Any --', 'k_ft_999')
				}
""" comment
a - 30 -59
b - 60 - 89
c - 90 - 119
d - 120 - 149
e - 150 179
f - 180 - 209
g - 210 - 239
h - 240 - 269
i - 270 - 299
j - 300 - 329
k - 330 - 359
l - 360 - 389
m - 390 - 419
n - 420 - 449
o - 450 - 479
p - 480 - 509
q - 510 - 539
r - 540 - 569
s - 570 - 599
t - 600 - 629
u - 630 - 659
v - 660 - 689
w - 690 - 719
x - 720 - 749
y - 750 - 779
z - 780 - 809
"""
def k_category_gettabname(category_id):
	return g_category_map[category_id][1]

def k_category_getname(category_id):
	if category_id not in g_category_map:
		return False
	return g_category_map[category_id][0]

g_state_map={
			'MY-14':'Kuala Lumpur',
			'MY-01':'Johor',
			'MY-02':'Kedah',
			'MY-03':'Kelantan',
			'MY-04':'Melaka',
			'MY-05':'Negeri Sembilan',
			'MY-06':'Pahang',
			'MY-07':'Pulau Pinang',
			'MY-08':'Perak',
			'MY-09':'Perlis',
			'MY-10':'Selangor',
			'MY-11':'Terengganu',
			'MY-12':'Sabah',
			'MY-13':'Sarawak',
			'MY-15':'Labuan',
			'MY-16':'Putrajaya',
			ANY_STATE:'-- Any --'
			}

def k_state_getname(divcode):
	if divcode in g_state_map:
		return g_state_map[divcode]
	else:
		return ''
