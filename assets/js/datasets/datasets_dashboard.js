var integrityEnv = {
    "isCurrentRateUSD": true,
    "productPrices":{
        "m12":{
            "conservative":"10000",
            "classic":"10000",
            "confident":null
        },
        "m6":{
            "conservative":"7000",
            "classic":"7000",
            "confident":null
        },
        "m3":{
            "conservative":"5000",
            "classic":"5000",
            "confident":"5000"
        },
        "m1":{
            "conservative":"3000",
            "classic":"3000",
            "confident":"3000"
        }
    },
    "productReturns":{
        "m12":{
            "conservative":-10000,
            "classic":-10000,
            "confident":null
        },
        "m6":{
            "conservative":-7000,
            "classic":-7000,
            "confident":null
        },
        "m3":{
            "conservative":-5000,
            "classic":-5000,
            "confident":-5000
        },
        "m1":{
            "conservative":-3000,
            "classic":-3000,
            "confident":-3000
        }
    },
    "productPie":{
        "conservative":{
            "datasets":[{
                "data":[5,10,5,10,5,65]
            }],
            "labels":["IOT","EOS","ZEC","ETH","NEO","BTC"]
        },
        "classic":{
            "datasets":[{
                "data":[10,15,10,5,10,50]
            }],
            "labels":["IOT","EOS","ZEC","BCH","NEO","BTC"]
        },
        "confident":{
            "datasets":[{
                "data":[15,15,15,5,5,5,5,5,5,25]
            }],
            "labels":["IOT","EOS","ZEC","TRX","MANA","XLM","BAT","SPK","YOYOW","BTC"]
        }
    },
    "totalHistogramm":{
        "m12":{
            "datasets":[
                {
                    "label": " Conservative",
                    "data": []
                },
                {
                    "label":" Classic",
                    "data":[]
                },
                {
                    "label":" Confident",
                    "data":[]
                }
            ],
            "labels":[]
        },
        "m6":{
            "datasets":[
                {
                    "label":" Conservative",
                    "data":[18,42,45,55,57,55,56,57,56,57,122,124,121,123,137,131,132,55,52,75,73,75,72,124,131,113,97,91,72,75,73,75,52,54,51,53,67,61,62,65,63,75,72,74,71,73,77,71,82,85,83,85,82,94,91,93,43,45,46,44,45,46,47,46,45,46,97,121,122,125,123,125,135,133,135,132,134,131,123,127]
                },
                {
                    "label":" Classic",
                    "data":[137,131,132,55,52,75,73,75,72,124,131,113,97,91,72,75,73,75,52,54,51,53,67,61,62,65,63,75,72,74,71,73,77,71,82,85,83,85,82,94,91,93,43,45,46,44,45,46,47,46,45,46,97,121,122,18,42,45,55,57,55,56,57,56,57,122,124,121,123,125,123,125,135,133,135,132,134,131,123,127]
                },
                {
                    "label":" Confident",
                    "data":[82,94,91,93,43,45,46,44,45,46,47,46,45,46,97,121,122,125,123,18,42,45,55,57,55,56,57,56,57,122,124,121,123,137,131,132,55,52,75,73,75,72,124,131,113,97,91,72,75,73,75,52,54,51,53,67,61,62,65,63,75,72,74,71,73,77,71,82,85,83,85,125,135,133,135,132,134,131,123,127]
                }
            ],
            "labels":['Jun.1','Jun.2','Jun.3','Jun.4','Jun.5','Jun.6','Jun.7','Jun.8','Jun.9','Jun.10','Jun.11','Jun.12','Jun.13','Jun.14','Jun.15','Jun.16','Jun.17','Jun.18','Jun.19','Jun.20','Jun.21','Jun.22','Jun.23','Jun.24','Jun.25','Jun.26','Jun.27','Jun.28','Jun.29','Jun.30','Jul.1','Jul.2','Jul.3','Jul.4','Jul.5','Jul.6','Jul.7','Jul.8','Jul.9','Jul.10','Jul.11','Jul.12','Jul.13','Jul.14','Jul.15','Jul.16','Jul.17','Jul.18','Jul.19','Jul.20','Jul.21','Jul.22','Jul.23','Jul.24','Jul.25','Jul.26','Jul.27','Jul.28','Jul.29','Jul.30','Jul.31','Aug.1','Aug.2','Aug.3','Aug.4','Aug.5','Aug.6','Aug.7','Aug.8','Aug.9','Aug.10','Aug.11','Aug.12','Aug.13','Aug.14','Aug.15','Aug.16','Aug.17','Aug.18','Aug.19']
        },
        "m3":{
            "datasets":[
                {
                    "label":" Conservative",
                    "data":[]
                },
                {
                    "label":" Classic",
                    "data":[]
                },
                {
                    "label":" Confident",
                    "data":[]
                }
            ],
            "labels":[]
        },
        "m1":{
            "datasets":[
                {
                    "label":" Conservative",
                    "data":[]
                },
                {
                    "label":" Classic",
                    "data":[]
                },
                {
                    "label":" Confident",
                    "data":[]
                }
            ],
            "labels":[]
        }
    }
};