import { expect } from 'chai';
import LazyLoader from './LazyLoader';

describe('utilities/LazyLoader', () => {
    let loader: LazyLoader<boolean>;

    before(() => {
        loader = new LazyLoader(() => Promise.resolve(true));
    });

    it('is not alive', () => {
        expect(loader.alive).to.equal(false);
    });

    it('loads', async () => {
        expect(await loader.get()).to.equal(true);
    });

    it('is cleared', () => {
        loader.clear();
        expect(loader.alive).to.equal(false);
    });
});