import {
    ThunkDispatch,
} from 'redux-thunk';

import {
    ISearchAction,
} from '../reducers/SearchReducer._types';

export interface IProps {
    dispatch: ThunkDispatch<any, any, any>;
}

export type IState = ISearchAction;