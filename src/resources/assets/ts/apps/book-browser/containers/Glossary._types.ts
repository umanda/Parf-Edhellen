import { ThunkDispatch } from 'redux-thunk';

import { ILanguageEntity } from '../../../connectors/backend/BookApiConnector._types';
import { IGlossaryState } from '../reducers/GlossaryReducer._types';
import { IGlossesState } from '../reducers/GlossesReducer._types';

export interface IProps extends IGlossaryState {
    dispatch?: ThunkDispatch<any, any, any>;
    glosses: IGlossesState;
    languages: ILanguageEntity[];
    unusualLanguages: ILanguageEntity[];
    isEmpty: boolean;
}
