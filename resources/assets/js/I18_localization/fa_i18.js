export default {
    /** GENERAL  */
    GENERAL: {
        // pagination and search table
        next: ' صفحه بعد ',
        back: ' صفحه قبل ',
        firstPage: 'اولین صفحه',
        LastPage: 'آخرین صفحه',
		resetTime: 'بازه زمانی به تمام ساعات روز تغییر پیدا کرد.',
        of: 'از',
        all: 'همه',
        page: 'صفحه',
        searchFeild: 'جستجوی در جدول',
        rowsperpage: 'تعداد',


        /** languge زبان */
        fa: 'فارسی',
        en: 'انگلیسی',
        message: 'تغییر زبان با موفقیت انجام شد',

        /** منوی اصلی */
        dashboard: 'داشبورد',
        home: 'خانه',
        answered: 'تماس های پاسخ داده شده',
        unAnswered: 'تماس های بدون پاسخ',
        distribution: 'امار توزیع تماس ها',
        operator: 'آمار کارشناس',
        search: 'جستجو',
        realTime: 'نمایش زنده',
        setting: 'تنظیمات',

        /** tool tip export pdf, excel and csv */
        pdfExport: 'PDF',
        excelExport: 'EXCEL',
        csvExport: 'CSV',

        /** multi select option*/
        noResult: 'نتیجه ای یافت نشده',
        select: 'انتخاب کنید',

        empty: 'عدم مقدار دهی',

        day: ' روز ',
        hour: ' ساعت ',
        minute: ' دقیقه ',
        secend: ' ثانیه ',

        percentage: ' درصد ',
        call: ' تماس ',
        person: ' نفر ',



        // روزهای هفته
        Saturday: 'شنبه',
        Sunday: 'یک شنبه',
        Monday: 'دو شنبه',
        Tuesday: 'سه شنبه',
        Wednesday: 'چهار شنبه',
        Thursday: 'پنج شنبه',
        Friday: 'جمعه',
        /** گزارش اطلاعات در بالای تمام صفحات سمت راست */
        report: {
            queue: 'صف ',
            title: 'گزارش اطلاعات',
            fromFilter: ' تاریخ شروع',
            toFilter: ' تاریخ پایان',
            range: 'بازه زمانی',
        },

        /** event */
        ABANDON: 'از دست رفته',
        AGENTDUMP: 'AGENTDUMP',
        AGENTLOGIN: 'ورود کارشناس',
        AGENTCALLBACKLOGIN: 'ورود کارشناس کال بک',
        AGENTLOGOFF: 'خروج کارشناس',
        AGENTCALLBACKLOGOFF: 'خروج کارشناس کال بک',
        COMPLETEAGENT: 'پایان مکالمه توسط کارشناس',
        COMPLETECALLER: 'پایان مکالمه توسط مشتری',
        CONFIGRELOAD: 'بارگزاری تنظیمات',
        CONNECT: 'آغاز مکالمه',
        ENTERQUEUE: 'ورود به صف',
        EXITWITHKEY: 'خروج با وارد کردن عدد',
        EXITWITHTIMEOUT: 'خروج به محض پایان مهلت انتظار',
        QUEUESTART: 'شروع به کار صف',
        SYSCOMPAT: 'SYSCOMPAT',
        TRANSFER: 'انتقال مکالمه',
        PAUSE: 'شروع وقفه',
        UNPAUSE: 'پایان وقفه',
        ADDMEMBER: 'افزودن کارشناس',
        REMOVEMEMBER: 'حذف کارشناس',
        RINGNOANSWER: 'عدم پاسخگویی',
		RINGCANCELED: 'لغو زنگ خوردن',
		
		/**THEME**/
		lightTheme: 'پوسته روشن',
		darkTheme: 'پوسته تیره',
		orangeTheme: 'پوسته نارنجی',
		blueTheme: 'پوسته آبی',

    },

    /** HOME PAGE */
    HOME: {
        /** موجود */
        available: ' موجود',
        /** انتخاب */
        selected: '  انتخاب شده',
        /**انتخاب صف */
        queueTitle: ' انتخاب صف',


        /** راهنما صف */
        queueGuide: 'صف های مورد نظر خود را برای اعمال گزارشات انتخاب کنید.',
        // انتخاب کارشناس 
        agentTitle: '  انتخاب کارشناس ',
        /** راهنمای کارشناس */
        agentGuide: 'به صورت پیش فرض فقط کارشناس های تعریف شده در صف ها نمایش داده می شود، در صورت انتخاب گزینه نمایش همه، کارشناس های گذشته که در اکنون در صف نیستند نیز برای انتخاب نمایش داده می شوند.',
        /** check box show all agent */
        showAllAgent: 'نمایش همه',


        /** بازه زمانی را انتخاب کنید */
        shortCutTitle: ' بازه زمانی را انتخاب کنید',

        /** filter */
        today: ' امروز',
        yesterday: ' دیروز',
        currentWeek: ' این هفته',
        lastWeek: ' هفته گذشته',
        inMonth: ' این ماه',
        currentMonth: 'ماه جاری',
        last3Month: ' سه ماه گذشته',
        /** زمان  */
        fromTime: ' زمان شروع',
        toTime: ' زمان پایان',
        /** تاریخ */
        fromFilter: ' تاریخ شروع',
        toFilter: ' تاریخ پایان',


        // دکمه نمایش گزارش 
        btnSubmit: ' نمایش گزارش '
    },

    /** ANSWERED PAGE */
    ANS: {
        /** main titile */
        title: 'گزارش تماس های پاسخ داده شده',

        /** عنوان جزئیات  */
        detail: {
            title: 'تماس های پاسخ داده شده',
            answered: 'تماس های پاسخ داده شده :',
            avgTime: 'میانگین طول تماس :',
            time: 'مجموع مدت مکالمه :',
            avgDelay: 'میانگین زمان انتظار :',

        },
        /** Answered by Agent */
        agent: {
            GUIDE: 'گزارش جزئیات تماس های پاسخ داده شده توسط هر کارشناس',
            title: ' تماس های پاسخ داده شده توسط کارشناس ',
            agent: ' نام کارشناس ',
            received: ' تعداد مکالمه ',
            completed: ' ',
            transferred: ' ',
            pCalls: ' درصد تعداد مکالمه ',
            time: ' مدت مکالمه ',
            pTime: ' درصد مدت مکالمه ',
            avgTime: ' میانگین مدت مکالمه ',
            ringTime: ' ',
            wait: ' زمان انتظار ',
            avgWait: ' میانگین زمان انتظار ',
            maxWait: ' '
        },
        /** Answered by Queue */
        queue: {
            GUIDE: 'گزارش ',
            title: 'تماس های پاسخ داده شده توسط صف',
            queue: 'صف',
            received: 'تعداد',
            pCalls: ' درصد تعداد مکالمه ',
        },
        /** Service Level */
        service: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'سطح سرویس',
            answered: 'پاسخ',
            count: 'تعداد',
            pCount: 'درصد پاسخ',
            delta: 'دلتا',
            lessThan: 'کمتر از',
        },
        // Disconnection Cause
        disconnection: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'دلیل قطع شدن مکالمه',
            received: 'تعداد',
            event: 'دلیل',
            pCount: 'درصد پاسخ',
            hangUp: 'قطع مکالمه توسط',
            agent: 'کارشناس',
            caller: 'مشتری'
        },
        // Answered by Call Length
        byTime: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'تماس های پاسخ داده شده بر اساس مدت مکالمه',
            duration: 'مدت مکالمه',
            received: 'تعداد تماس ها',
            completed: 'پاسخ داده شده',
            transferred: 'انتقال داده شده',
            pCount: 'درصد پاسخ',
            time: 'مدت مکالمه',
            pTime: 'درصد مکالمه',
            avgTime: 'میانگین مکالمه',
            wait: ' مدت انتظار',
            avgWait: 'میانگین انتظار',
            maxWait: 'حداکثر انتظار'
        },
        /** Transfers Calls */
        transfers: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'تماس های منتقل شده',
            agent: 'نام کارشناس',
            to: 'انتقال به',
            count: 'تعداد',
        },
        // Answered Calls Detail
        ansDetail: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'جزئیات تماس های پاسخ داده شده',
            duration: 'تاریخ ',
            queue: 'صف',
            agent: ' نام کارشناس',
            event: 'رویداد',
            ringTime: 'زمان زنگ زدن',
            time: 'مدت مکالمه',
            wait: ' مدت انتظار',
            action: ' عملیات ',
			voice: 'فایل صوتی مکالمه',
			phone: 'شماره مشتری',
        },


    },

    /** UnAnswered PAGE */
    UN_ANS: {
        /** main titile */
        title: 'گزارش تماس های بدون پاسخ',

        /** عنوان جزئیات  */
        detail: {
            title: 'تماس های بدون پاسخ',
            unAnswered: 'تعداد تماس بدون پاسخ',
            delay: ' میانگین مدت انتظار قبل از قطع تماس',
            num: 'میانگین نوبت هنگام ورود به صف',
            avgDelay: '  میانگین زمان انتظار ',
        },
        // Disconnection Cause
        disconnection: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'دلیل قطع شدن مکالمه',
            received: 'تعداد',
            event: 'دلیل',
            pCount: 'درصد ',
            RINGCANCELED: 'انصراف از سمت مشتری',
            EXITWITHTIMEOUT: 'محدودیت زمانی صف',
            EXITWITHKEY: 'انصراف با زدن کلید',

        },
        //    تماس های بدون پاسخ  در صف 
        queue: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'تماس های بدون پاسخ در صف',
            received: 'تعداد',
            queue: 'صف',
            pCount: 'درصد ',
        },

    },

    /** DISTRIBUTION PAGE */
    DIS: {
        title: 'گزارش آمار توزیع تماس ها',
        /** عنوان جزئیات  */
        detail: {
            title: 'مجموع',
            answered: ' تعداد تماس پاسخ داده شده:',
            unAnswered: 'تعداد تماس بدون پاسخ',
            login: 'ورود کارشناس',
            logout: 'خروج کارشناس',
        },
        // میانگین زمان انتظار در ساعت
        wait: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'میانگین زمان مکالمه و انتظار در روز',
            data: 'تاریخ',
            answered: 'پاسخ داده شده',
            pAnswered: ' درصد پاسخ داده شده',
            unAnswered: ' بدون پاسخ',
            pUnAnswered: ' درصد بدون پاسخ',
            avgTime: ' میانگین مدت مکالمه ',
            avgWait: ' میانگین زمان انتظار ',
            login: 'ورود ',
            logout: 'خروج ',

        },
        // پراکندگی تماس ها در ساعت
        dispersion: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'پراکندگی تماس ها در ساعت',
            time: 'ساعت',
            answered: 'پاسخ داده شده',
            pAnswered: ' درصد پاسخ داده شده',
            unAnswered: ' بدون پاسخ',
            pUnAnswered: ' درصد بدون پاسخ',
            avgTime: ' میانگین مدت مکالمه ',
            avgWait: ' میانگین زمان انتظار ',
            login: 'ورود ',
            logout: 'خروج ',
        },
        // پراکندگی تماس ها در روزهای هفته
        dispersionInWeek: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'پراکندگی تماس ها در روزهای هفته',
            day: 'روز',
            answered: 'پاسخ داده شده',
            pAnswered: ' درصد پاسخ داده شده',
            unAnswered: ' بدون پاسخ',
            pUnAnswered: ' درصد بدون پاسخ',
            avgTime: ' میانگین مدت مکالمه ',
            avgWait: ' میانگین زمان انتظار ',
            login: 'ورود ',
            logout: 'خروج ',
        },
        // پراکندگی تماس ها در ماه
        dispersionInMonth: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'پراکندگی تماس ها در ماه',
            month: 'ماه',
            answered: 'پاسخ داده شده',
            pAnswered: ' درصد پاسخ داده شده',
            unAnswered: ' بدون پاسخ',
            pUnAnswered: ' درصد بدون پاسخ',
            avgTime: ' میانگین مدت مکالمه ',
            avgWait: ' میانگین زمان انتظار ',
            login: 'ورود ',
            logout: 'خروج ',
        },
        //نمودار پاسخ داده شده / بدون پاسخ در ساعت
        chartAnswered: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'پاسخ داده شده / بدون پاسخ در ساعت',
            answered: 'پاسخ داده شده',
            unAnswered: 'پاسخ داده نشده',
        },
        // نمودار میانگین زمان صحبت در ساعت
        chartTimeAnswered: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'میانگین زمان صحبت در ساعت',
            avgTime: 'متوسط زمان صحبت',
        },
        // نمودار میانگین زمان انتظار در ساعت
        chartDelayAnswered: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'میانگین زمان انتظار در ساعت',
            avgDelay: 'متوسط زمان انتظار',
        },
        // نمودار پاسخ به تماس ها براساس روزهای هفته
        chartAnsweredWeek: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: ' پاسخ به تماس ها براساس روزهای هفته',
            count: 'تعداد',
        },
        //  نمودار میانگین مدت تماس براساس روزهای هفته
        chartTimeAnsweredWeek: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'نمودار میانگین مدت تماس براساس روزهای هفته',
            avgAnswered: 'متوسط زمان پاسخگویی',
        },





    },

    /** OPERATOR PAGE */
    OPERATOR: {
        /** main titile */
        title: 'کارشناس ها',

        /** عنوان جزئیات  */
        detail: {
            title: 'مجموع',
            Agents: 'تعداد کارشناس',
            avgSession: 'میانگین مدت حضور',
            shortestSession: 'کوتاه ترین مدت حضور',
            longestSession: 'بیشترین میزان حضور',
            totalSession: 'جمع کل حضور',
            undefine: 'نا مشخص'
        },
        // Agent Availability
        agent: {
            GUIDE: 'اطلاعات کلی در خصوص میزان دسترسی پذیری و فعالیت کارشناس ها',
            title: 'دسترس پذیری کارشناس',
            aht: 'AHT',
            idleTime: 'زمان بیکاری',
            holdTime: 'زمان انتظار',
            wrapupTime: 'زمان جمع بندی',
            talkTime: 'زمان مکالمه',
            pauseTime: 'زمان وقفه',
            pauses: 'وقفه',
            pSession: '% حضور',
            sessionTime: 'زمان حضور',
            sessions: 'حضور',
            failedOut: 'شکست خورده',
            failed: 'ناموفق',
            rejected: 'رد تماس',
            answered: 'پاسخ داده ',
            agent: 'کارشناس'
        },
        /** Pause Detail */
        pause: {
            GUIDE: 'گزارش جزئیات وقفعه های گرفته شده',
            title: 'جزئیات وقفه',
            agent: 'کارشناس',
            total: 'مجموع',
        },
        /** Call Disposition by Agent */
        answered: {
            GUIDE: 'گزارش بر اساس نتیجه یک مکالمه',
            title: 'مدیریت تماس توسط کارشناس',
            agent: 'کارشناس',
            completeByCaller: 'پایان تماس توسط مشتری',
            completeByAgent: 'پایان تماس توسط کارشناس',
            transfer: 'انتقال مکالمه',
            failed: 'ناموفق',
            rejected: 'رد تماس',
            failedOut: 'شکست خورده',
        },
        // Full Agent Report
        report: {
            GUIDE: 'گزارشی کامل از تمامی اتفاقاتی که برای یک کارشناس افتاده است.',
            title: 'گزارش کامل کارشناس',
            date: 'تاریخ',
            data1: 'داده',
            duration: ' زمان',
            queue: 'صف',
            agent: 'کارشناس',
            event: 'رویداد',
            duration: 'مدت زمان',
        }
    },

    /** SEARCH PAGE */
    SEARCH: {
        /** main titile */
        title: 'جستجوی پیشرفته',
        /** box search */
        search: {
            GUIDE: null,
            title: 'جستجو',
            date: 'تاریخ',
            queue: 'صف',
            agent: 'کارشناس',
            event: 'رویداد',
            wait: 'زمان انتظار',
            time: 'زمان مکالمه',
            number: 'شماره',
            callid: 'شناسه تماس',
        }
    },

    /** Setting PAGE */
    SETTING: {
        /** main titile */
        title: 'تنظیمات',

        /** backup   */
        backup: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'نسخه پشتیبان',
            content: 'برای گرفتن نسخه پشتیبان و حذف داده های قدیمی بر روی دکمه گرفتن خروجی کلیک کنید ',
            btn: 'گرفتن خروجی'
        },
        /** lang   */
        lang: {
            GUIDE: '  لورم ایپسوم یا طرح‌نما فیکی نشانگر چگونگی نوع و اندازه فونت و ظاهر متن می باشد',
            title: 'زبان برنامه',
            content: 'انتخاب زبان',
            btn: ' تغییر زبان'
        },

        theme: {
            content: 'پوسته گزارشگیری را به روشن یا تاریک تغییر بدهید',
            title: 'انتخاب پوسته',
            btn: ' روشن/تیره'
        },

    },

	
}