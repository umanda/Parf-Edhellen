import { combineReducers } from 'redux';

import { DeriveRootReducer } from '@root/_types';

import FragmentsReducer from './FragmentsReducer';
import LatinFragmentsReducer from './LatinFragmentsReducer';
import SelectionReducer from './SelectionReducer';
import SentenceReducer from './SentenceReducer';
import TengwarFragmentsReducer from './TengwarFragmentsReducer';
import TranslationsReducer from './TranslationsReducer';

const reducers = {
    fragments: FragmentsReducer,
    latinFragments: LatinFragmentsReducer,
    selection: SelectionReducer,
    sentence: SentenceReducer,
    tengwarFragments: TengwarFragmentsReducer,
    translations: TranslationsReducer,
};

export type RootReducer = DeriveRootReducer<typeof reducers>;

export default combineReducers(reducers);
