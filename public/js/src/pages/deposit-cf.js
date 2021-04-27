const cfSelect = (() => {
    const inpEvent = new Event('change');

    const choice = (slct, opt, inp) => {
        inp && (inp.value = opt.dataset.value) && (inp.dispatchEvent(inpEvent));

        let curr = slct.querySelector('.selected')
        curr && curr.classList.remove('selected');

        opt.classList.add('selected');

        slct.querySelector('div').innerHTML = opt.innerHTML;
        slct.classList.remove('opened')
    }
    document.body.addEventListener('click', event => {
        if( !(event.target.closest('.select-cf')) ) {
            document.querySelectorAll('.select-cf').forEach(slct => slct.classList.remove('opened'));
        }
    });

    return {
        init: slct => {
            let curr = slct.querySelector('.selected');
            if (!curr) curr = slct.querySelector('li');

            let inp = slct.querySelector('input');

            choice(slct, curr, inp);

            slct.querySelectorAll('li').forEach( opt => opt.addEventListener('click', () => choice(slct, opt)));
            slct.addEventListener('click', event => {
                let opt = event.target.closest('li');
                if (opt) {
                    choice(slct, opt, inp);
                } else {
                    slct.classList.add('opened');
                }
                document.querySelectorAll('.select-cf').forEach(el => {
                    if (el !== slct) el.classList.remove('opened');
                });
            });
        }
    }
})();
document.querySelectorAll('.select-cf').forEach(slct => cfSelect.init(slct));

const depositCrypto = (dc) => {
    if (!dc) return;

    const cryptoTabs = (() => {

        const move = (num, wrapper) => {
            wrapper.style.transform = `translate3d(${(1-num)*100}%, 0,0)`;
        };

        return {
            init: (tabs, itemsWrapper) => {
                tabs.forEach( tab => {
                    tab.addEventListener('click', event => {
                        event.preventDefault();
                        let curr = tab.parentElement.querySelector('.current');
                        curr && curr.classList.remove('current');

                        let nextNum = tab.dataset.tab;
                        if (nextNum > 0) move(nextNum, itemsWrapper);

                        tab.classList.add('current');
                    });
                });
            }
        }
    })();

    const copyLink = link => {
        if (!link) return;

        const copy = str => {
            let tmp = document.createElement('INPUT'),
                focus = document.activeElement;

            tmp.value = str;

            document.body.appendChild(tmp);
            tmp.select();
            document.execCommand('copy');
            document.body.removeChild(tmp);
            focus.focus();

            // link.classList.add('blink');
            link.setAttribute('title', 'Copied!');
            setTimeout( () => {
                // link.classList.remove('blink');
                link.removeAttribute('title');
            }, 1500);
        };

        link.addEventListener('click', e => {
            e.preventDefault();
            copy(link.innerHTML);
        }, false );
    };
    dc.querySelectorAll('.deposit-cf-crypto-address-link').forEach( el => copyLink(el) );

    const cryptoCoins = () => {
        let coinsWrapper = dc.querySelector('.deposit-cf-crypto-networks');
        let coinsItem = dc.querySelectorAll('.deposit-cf-crypto-network');
        coinsItem.forEach( item => {
            let tabs = item.querySelectorAll('.dashboard-menu-tabs-item');
            let wrapper = item.querySelector('.deposit-cf-crypto-addresses-container');
            tabs.length && wrapper && cryptoTabs.init(tabs, wrapper);
        });

        let balance = dc.querySelector('.deposit-cf-crypto-balance');

        let inp = dc.querySelector('#crypto-coin');

        if (!inp || !coinsWrapper) return;

        const choice = () => {
            let coin = inp.value;
            let selected = dc.querySelector(`#crypto-${coin}`);
            balance && (balance.dataset.coin = coin);
            balance.querySelector('.deposit-cf-crypto-balance-value').innerHTML = dc.querySelector(`input[name=balance_${coin}]`).value;
            if (selected) {
                let offset = selected.offsetLeft;
                coinsWrapper.style.transform = `translate3d(-${offset}px, 0,0)`;
            }
        };
        inp.addEventListener('change', () => choice());
        choice();
    };
    cryptoCoins();
};
depositCrypto(document.querySelector('.deposit-cf-crypto'));

const depositFiat = ( df => {
    if (!df) return;

    const fiatMethods = (() => {
        let methods = df.querySelector('.deposit-cf-fiat-methods');
        if (!methods) return {
            setFirst: () => {
            }, getFee: () => {
            }
        };

        const inpChange = new Event('change');

        let inp = methods.querySelector('input[type=hidden]');
        let fee = 0;

        const choice = el => {
            let selected = methods.querySelector('.checked');
            if (selected) selected.classList.remove('checked');
            el.classList.add('checked');
            if (inp && el.dataset.method) {
                inp.value = el.dataset.method;
                fee = 1 * el.dataset.fee;
                inp.dispatchEvent(inpChange);
            }
        };

        methods.addEventListener('click', evt => {
            evt.preventDefault();
            let el = evt.target.closest('.deposit-cf-fiat-method');
            if (el) choice(el);
        });

        return {
            setFirst: methodsItem => {
                let first = methodsItem ? methodsItem.querySelector('.deposit-cf-fiat-method') : false;

                let selected = methods.querySelector('.checked');
                if (selected) selected.classList.remove('checked');

                if (first) {
                    first.classList.add('checked');
                    fee = first.dataset.fee;
                    inp.value = first.dataset.method;
                } else {
                    fee = 0;
                    inp.value = '';
                    console.log(`${currency} — currency  not set`);
                }
            },
            getFee: () => 1 * fee
        }
    })();

    const fiatAmount = (() => {
        let container = df.querySelector('.deposit-cf-fiat-payinfo');
        if (!container) return {
            setCurrency: () => {
            }, setFee: () => {
            }
        };

        let btn = container.querySelector('.deposit-cf-fiat-btn');

        let inp = container.querySelector('#fiat-amount');
        let fee = container.querySelector('#fiat-amount-fee');
        let total = container.querySelector('#fiat-amount-total');
        let currencies = container.querySelectorAll('.fiat-amount-currency');

        inp.addEventListener('input', () => {
            let val = parseFloat(inp.value);
            isNaN(val) ? inp.value = '' : inp.value = val;
            fiatAmount.setFee();
        });

        let method = df.querySelector('#fiat-method');
        method && method.addEventListener('change', () => fiatAmount.setFee());

        return {
            setCurrency: currency => {
                inp.parentElement.dataset.currency = currency;
                currencies.forEach(el => el.innerHTML = currency);
            },
            setFee: () => {
                let amount = isNaN(inp.value) ? 0 : inp.value;
                let feePercent = fiatMethods.getFee();

                if (btn && amount > 0) {
                    btn && btn.classList.remove('disabled');
                } else {
                    btn && btn.classList.add('disabled');
                }

                fee.innerHTML = `${(1 * amount * feePercent).toFixed(4)}`;
                total.innerHTML = `${(1 * amount * (1 - feePercent)).toFixed(4)}`;
            }
        }

    })();

    const fiatCurrency = (() => {
        let inp = df.querySelector('#fiat-coin');
        if (!inp) return;

        const choice = () => {
            let currency = inp.value;
            fiatAmount.setCurrency(currency);

            let currMethods = df.querySelector('.deposit-cf-fiat-methods-item.selected');
            let selectedMethods = df.querySelector(`#fiat-${currency}`);

            if (currMethods && currMethods === selectedMethods) return;

            currMethods && currMethods.classList.remove('selected');

            if (selectedMethods) {
                selectedMethods.classList.add('selected');
                fiatMethods.setFirst(selectedMethods);
                fiatAmount.setFee();
            }
        };
        inp.addEventListener('change', () => choice());
        choice();
    })();
})(document.querySelector('.deposit-cf-fiat'));
